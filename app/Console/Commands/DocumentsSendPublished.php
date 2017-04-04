<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use File;
use Mail;
use Carbon\Carbon;

use App\User;
use App\Role;
use App\UserEmailSetting;
use App\UserSentDocument;
use App\Document;
use App\EditorVariant;
use App\PublishedDocument;
use App\Helpers\ViewHelper;
use App\Classes\PdfWrapper;

class DocumentsSendPublished extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:send-published';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send documents via emails according to their publish date.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->newsId = 1;
        $this->rundId = 2;
        $this->qmRundId = 3;
        $this->isoDocumentId = 4;
        $this->formulareId = 5;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $recievers = UserSentDocument::all();
        foreach($recievers as $reciever){
            // Send email ONLY if it is NOT SENT AND if PUBLISH DATE is TODAY
            if($reciever->sent == false){
                $document = Document::find($reciever->document_id);
                if(Carbon::today()->toDateString() == Carbon::parse($document->date_published)->toDateString()){
                    $this->sendPublishedDocuments($document, $reciever);
                    $reciever->sent = true;
                    $reciever->save();
                }
            }
        }
        
    }
    
    private function sendPublishedDocuments($document, $reciever){
        
        $emailSetting = $reciever->userEmailSetting;
        
        // Generate temporary pdf export of the document variants
        // $documentPdf = $this->generatePdfObject($document->id, null, $emailSetting->user_id);
        
        // Fill the email container class with adequate values
        $mailContent = new \StdClass();
        $mailContent->subject = 'Benachrichtigung über ein neues Dokument im Intranet: "'. $document->name .'"';
        $mailContent->title = 'Benachrichtigung über ein neues Dokument im Intranet: "'. $document->name .'"';
        $mailContent->fromEmail = 'info@neptun.de';
        $mailContent->fromName = 'Informationsservice';
        $mailContent->link = url('dokumente/' . $document->id);
            
        // Get user data for the email setting
        $user = User::find($emailSetting->user_id);
        
        // Skip email sending if user has the email sending flag disabled
        // Skip email sending if document type has no publish sending flag
        if(($user->email_reciever == false) || ($document->documentType->publish_sending == false)) continue;
        
        // Check if the role assigned to the email setting is a system role
        $role = Role::find($emailSetting->email_recievers_id);
        if(isset($role->system_role)) $systemRole = $role->system_role; 
        
        // Alternate method to generate pdfs for each viewable variant (add as attachments)
        $documentPdfs = array();
        $documentVariants = ViewHelper::documentVariantPermission($document, $user->id, true)->variants;
        foreach($documentVariants as $dv) {
            $documentPdfs[] = [
                'filePath' => $this->generatePdfObject($dv->document_id, $dv->variant_number, $user->id),
                'fileName' => str_slug($dv->document->id .'-v'. $dv->variant_number .'-'. $dv->document->name) . '.pdf'
            ];
        }
        
        // Sending method: email (This method is avaliable to all users)
        if($emailSetting->sending_method == 1){
            // Check if the document type is corresponding the mailing settings
            if(in_array($emailSetting->document_type_id, [0, $document->document_type_id])){
                
                $mailContent->toEmail = $emailSetting->recievers_text;
                $mailContent->toName = $user->first_name .' '. $user->last_name;
                
                // Readout user email options and send
                $sent = Mail::send('email.publishedDocuments', ['content' => $mailContent, 'user' => $user, 'document' => $document, 'attachments' => false], 
                    function ($message) use ($mailContent, $document) {
                        $message->from($mailContent->fromEmail, $mailContent->fromName);
                        $message->to($mailContent->toEmail, $mailContent->toName);
                        $message->subject($mailContent->subject);
                });
                
                // Log sending
                $logText = "E-Mail: ". $mailContent->toEmail ."; UserID: ". $user->id ."; DocumentID: ". $document->id ."; ";
                ViewHelper::logSendPublished($sent, $logText);
            }
        }
        
        // Sending method: email + attachment (ONLY system roles can recieve documents as attachments)
        if($systemRole && ($emailSetting->sending_method == 2)){
            // Check if the document type is corresponding the mailing settings
            if(in_array($emailSetting->document_type_id, [0, $document->document_type_id])){
                
                $mailContent->toEmail = $emailSetting->recievers_text;
                $mailContent->toName = $user->first_name .' '. $user->last_name;
                
                // Get document attachments by variant permissions, for specified user id, then send them as email attachments
                $documentAttachments = array();
                
                $documentVariants = ViewHelper::documentVariantPermission($document, $user->id, true)->variants;
                foreach($documentVariants as $variant){
                    foreach($variant->EditorVariantDocument as $k => $docAttach){
                        if( $docAttach->document_id != $document->id ){
                            foreach( $docAttach->document->documentUploads as $key => $docUpload){
                                if( $key == 0 ){
                                    $documentAttachments[] = [
                                        'filePath' => base_path() . '/public/files/documents/'. $docAttach->document->id .'/'. $docUpload->file_path, 
                                        'fileName' => str_slug($docAttach->document->id .'-'. $docAttach->document->name) . '.pdf'
                                    ];
                                }
                            }
                        }
                    }
                }
                    
                // Readout user email options and send
                $sent = Mail::send('email.publishedDocuments', ['content' => $mailContent, 'user' => $user, 'document' => $document, 'attachments' => true], 
                    // function ($message) use ($mailContent, $document, $documentPdf, $documentAttachments) {
                    function ($message) use ($mailContent, $document, $documentPdfs, $documentAttachments) {
                        $message->from($mailContent->fromEmail, $mailContent->fromName);
                        $message->to($mailContent->toEmail, $mailContent->toName);
                        $message->subject($mailContent->subject);
                        // $message->attach($documentPdf, ['as' => str_slug($document->id .'-'. $document->name) . '.pdf', 'mime' => 'application/pdf']);
                        foreach ($documentPdfs as $documentPdf) {
                            $message->attach($documentPdf['filePath'], ['as' => $documentPdf['fileName'], 'mime' => 'application/pdf']);
                        }
                        foreach ($documentAttachments as $attachment) {
                            $message->attach($attachment['filePath'], ['as' => $attachment['fileName'], 'mime' => 'application/pdf']);
                        }
                });
                
                // Log sending
                $logText = "E-Mail: ". $mailContent->toEmail ."; UserID: ". $user->id ."; DocumentID: ". $document->id ."; ";
                ViewHelper::logSendPublished($sent, $logText);
            }
        }
        
        // Sending method: fax (This method sends the document via fax commands)
        if($emailSetting->sending_method == 3){
            // Check if the document type is corresponding the mailing settings
            if(in_array($emailSetting->document_type_id, [0, $document->document_type_id])){
                // Faxing commands
                // // Log sending
                // ViewHelper::logSendPublished($sent, $logText);
            }
        }
        
        // Delete generated pdf file
        // File::delete($documentPdf);
        
        // Delete generated pdf files
        foreach($documentPdfs as $pdf){
            File::delete($pdf['filePath']);
        }
    }
    
    
    /**
     * Generate a PDF object by document id (for further manipulation).
     *
     * @return string $filename
     */
    private function generatePdfObject($id, $variantNumber = null, $userId = null )
    {
        $publishedDocumentLink = PublishedDocument::where('url_unique', $id)->first();
        if ((ctype_alnum($id) && !is_numeric($id)) || $publishedDocumentLink != null) {
            $publishedDocs = PublishedDocument::where('url_unique', $id)->first();
            $id = $publishedDocs->document_id;
            $document = Document::find($id);
        } else {
            $document = Document::find($id);
        }
        
        $variantPermissions = ViewHelper::documentVariantPermission($document, $userId);
        if ($variantPermissions->permissionExists == false) {
            return false;
        }

        $datePublished = new Carbon($document->date_published);
        $dateNow = $this->getGermanMonthName(intval($datePublished->format('m')));
        $dateNow .= ' '.$datePublished->format('Y');

        // Extend this functionality
        if(isset($variantNumber)) {
            $variants = EditorVariant::where('document_id', $id)->where('variant_number', $variantNumber)->get();
            foreach ($variants as $variant) {
                $variant->hasPermission = true;
            }
        } else $variants = $variantPermissions->variants;
    
        // $document = Document::find($id);
        $margins = $this->setPdfMargins($document);

        $or = 'P';
        if ($document->landscape == true) {
            $or = 'L';
        }

        $pdf = new PdfWrapper;
        
        if ($document->document_type_id == $this->isoDocumentId) {
            $pdf->SetHTMLHeader(view('pdf.headerIso', compact('document', 'variants', 'dateNow'))->render());
            $pdf->SetHTMLFooter(view('pdf.footerIso', compact('document', 'variants', 'dateNow'))->render());
            $render = view('pdf.documentIso', compact('document', 'variants', 'dateNow'))->render();
        } else {
            if ($document->document_template == 1) {
                $render = view('pdf.document', compact('document', 'variants', 'dateNow'))->render();
                $pdf->SetHTMLFooter(view('pdf.footer', compact('document', 'variants', 'dateNow'))->render());
            } else {
                $render = view('pdf.new-layout-rund', compact('document', 'variants', 'dateNow'))->render();
                $header = view('pdf.new-layout-rund-header', compact('document', 'variants', 'dateNow'))->render();
                $footer = view('pdf.new-layout-rund-footer', compact('document', 'variants', 'dateNow'))->render();
                $pdf->SetHTMLHeader($header);
                $pdf->SetHTMLFooter($footer);
            }
        }

        $pdf->AddPage($or,$margins->left, $margins->right, $margins->top, $margins->bottom,$margins->headerTop, $margins->footerTop);
        $pdf->WriteHTML($render);

        $filename = sys_get_temp_dir().'/'.$id.'_'.md5(microtime()).'.pdf';
        if(isset($variantNumber)) {
            $filename = sys_get_temp_dir().'/'.$id.'v'.$variantNumber.'_'.md5(microtime()).'.pdf';
        }
        $pdf->save($filename);
        return $filename;
    }
    
    /**
     * Return german months.
     *
     * @param int $id     *
     * @return string
     */
    private function getGermanMonthName($id)
    {
        $months = array(
            1 => 'Januar', 2 => 'Februar', 3 => 'März', 4 => 'April', 5 => 'Mai', 6 => 'Juni', 7 => 'Juli', 8 => 'Ausgust',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Dezember',
            );

        return $months[$id];
    }

    /**
     * Return pdf margins.
     *
     * @param collection $document     *
     * @return object $margins
     */
    private function setPdfMargins($document)
    {
        $margins = new \StdClass();
        /* Set the document orientation */
        $margins->orientation = 'P';
        if ($document->landscape == true) {
            $margins->orientation = 'L';
        }

        /* End  Set the document orientation */

        if ($document->document_type_id == $this->isoDocumentId && $margins->orientation == 'P') {// if  iso document and orientation portrait
            $margins->left = 10;
            $margins->right = 10;
            $margins->top = 40;
            $margins->bottom = 25;
            $margins->headerTop = 0;
            $margins->footerTop = 5;
        } elseif ($document->document_type_id == $this->isoDocumentId && $margins->orientation == 'L') {// if  iso document and orientation landscape
            $margins->left = 10;
            $margins->right = 50;
            $margins->top = 30;
            $margins->bottom = 10;
            $margins->headerTop = 0;
            $margins->footerTop = 5;
        } elseif ($document->document_type_id != $this->isoDocumentId && $margins->orientation == 'P') {// if not iso document and orientation portrait
            $margins->left = 10;
            $margins->right = 8;
            $margins->top = 65;
            $margins->bottom = 30;
            $margins->headerTop = -0;
            $margins->footerTop = 0;

            if ($document->document_template == 1) {
                $margins->left = 10;
                $margins->right = 5;
                $margins->top = 10;
                $margins->bottom = 20;
                $margins->headerTop = 10;
                $margins->footerTop = 0;
            }
        } else { // if not iso document and orientation landscape
            $margins->left = 5;
            $margins->right = 0;
            $margins->top = 50;
            $margins->bottom = 10;
            $margins->headerTop = 0;
            $margins->footerTop = 0;
            if ($document->document_template == 1) {
                $margins->left = 5;
                $margins->right = 5;
                $margins->top = 10;
                $margins->bottom = 10;
                $margins->headerTop = 0;
                $margins->footerTop = 0;
            }
        }

        /* End Set the document margins */

        return $margins;
    }
    
}
