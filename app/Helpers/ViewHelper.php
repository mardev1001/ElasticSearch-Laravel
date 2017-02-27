<?php
namespace App\Helpers;

use Request;

use Auth;
use File;

use App\User;
use App\Mandant;
use App\MandantUser;
use App\MandantUserRole;
use App\PublishedDocument;
use App\DocumentCoauthor;
use App\EditorVariant;
use App\DocumentApproval;
use App\DocumentType;
use App\WikiPage;
use App\WikiRole;
use App\WikiCategory;
use App\WikiCategoryUser;
use App\InventoryCategory;
use App\InventorySize;
use App\InternalMandantUser;
use App\FavoriteCategory;

class ViewHelper
{


    /**
     * Generate and check input type text
     *
     * @param string $str
     * @param array $tags
     *
     * @return string $string
     */
    static function stripTags($str, $tags = array())
    {
        foreach ($tags as $tag) {
            $str = preg_replace('/<' . $tag . '[^>]*>/i', '', $str);
            $str = preg_replace('/<\/' . $tag . '>/i', '', $str);
        }

        return $str;
    }

    /**
     * Generate and check input type text
     *
     * @param string $inputName
     * @param array $data || string $data='' ( declared in FormViewComposer)
     * @param string $old
     * @param string $label
     * @param string $placeholder
     * @param bool $required
     * @return string $value || $old
     */
    static function setInput($inputName, $data, $old, $label = '', $placeholder = '', $required = false, $type = '', $classes = array(), $dataTags = array())
    {
        $string = '';
        if ($placeholder == '')
            $placeholder = $label;

        if ($type == '')
            $type = 'text';

        $string = view('partials.inputText',
            compact('inputName', 'data', 'type', 'label', 'old', 'placeholder', 'required', 'classes', 'dataTags')
        )->render();


        echo $string;
    }

    /**
     * Generate and check input type checkbox
     *
     * @param string $inputName
     * @param array $data || string $data='' ( declared in FormViewComposer)
     * @param string $old
     * @param string $label
     * @param bool $required
     * @return string $value || $old
     */
    static function setCheckbox($inputName, $data, $old, $label = '', $required = false, $classes = array(), $dataTags = array(), $number = -1)
    {
        $string = '';
        $string = view('partials.inputCheckbox',
            compact('inputName', 'classes', 'dataTags', 'data', 'label', 'old', 'required', 'number')
        )->render();

        echo $string;
    }

    /**
     * Generate and check input type textarea
     *
     * @param string $inputName
     * @param array $data || string $data='' ( declared in FormViewComposer)
     * @param string $old
     * @param string $label
     * @param string $placeholder
     * @param bool $required
     * @param array $classes
     * @param array $dataAttr
     * @return string $value || $old
     */
    static function setArea($inputName, $data, $old, $label = '', $placeholder = '', $required = false, $classes = array(), $dataTag = array(), $readonly = false, $parseBack = false)
    {
        $string = '';
        if ($placeholder == '')
            $placeholder = $label;

        $string = view('partials.inputTextarea',
            compact('inputName', 'data', 'label', 'old', 'placeholder', 'required', 'readonly', 'parseBack')
        )->render();


        echo $string;
    }


    /**
     * Generate and check input type textarea
     *
     * @param array $collections
     * @param string $inputName
     * @param array $data || string $data='' ( declared in FormViewComposer)
     * @param string $old
     * @param string $label
     * @param string $placeholder
     * @param bool $required
     * @param array $classes
     * @param array $dataAttr
     * @return string $value || $old
     */
    static function setSelect($collections = array(), $inputName, $data, $old, $label = '', $placeholder = '', $required = false, $classes = array(), 
    $dataTag = array(), $attributes = array(), $emptyOption=false)
    {
        if ($placeholder == '')
            $placeholder = $label;

        if ($old == '' && isset($data->$inputName) && !empty($data->$inputName))
            $value = $data->$inputName;
            
        $string = '';
        $string = view('partials.inputSelect',
            compact('collections', 'inputName', 'data', 'label', 'old', 'placeholder', 'required', 'classes', 'dataTag', 'attributes','emptyOption')
        )->render();


        echo $string;
    }

    /**
     * Generate and check input type textarea
     *
     * @param array $collections
     * @param string $inputName
     * @param array $data || string $data='' ( declared in FormViewComposer)
     * @param string $old
     * @param string $label
     * @param string $placeholder
     * @param bool $required
     * @param array $classes
     * @param array $dataAttr
     * @return string $value || $old
     */
    static function setUserSelect($collections = array(), $inputName, $data, $old, $label = '', $placeholder = '', $required = false,
                                  $classes = array(), $dataTag = array(), $attributes = array(), $emptyOption=false)
    {
        if ($placeholder == '')
            $placeholder = $label;

        if ($old == '' && isset($data->$inputName) && !empty($data->$inputName))
            $value = $data->$inputName;

        $string = '';
        $string = view('partials.inputUserSelect',
            compact('collections', 'inputName', 'data', 'label', 'old', 'placeholder', 'required',
            'classes', 'dataTag', 'attributes', 'emptyValue','emptyOption')
        )->render();


        echo $string;
    }

    /**
     * Generate and check input type textarea
     *
     * @param object array $userValues
     * @param string $value
     * @echo string 'selected'
     */
    static function setMultipleSelect($userValues, $value, $key = 'id')
    {
        foreach ($userValues as $userValue) {
            if ($userValue->$key == $value)
                echo 'selected ';
        }

    }

    /**
     * Generate and check input type textarea
     *
     * @param object array $userValues
     * @param string $value
     * @echo string 'selected'
     */
    static function setComplexMultipleSelect($collection, $relationship, $value, $key = 'id', $oneLessForeach = false, $dontShow = false)
    {
        if ($oneLessForeach == false) {
            if (count($collection))
                foreach ($collection as $col) {
                    foreach ($col->$relationship as $userValue) {
                        if ($userValue->$key == $value)
                            echo 'selected 2';
                    }
                }

        } else {
            if (count($collection->$relationship) > 0) {
                foreach ($collection->$relationship as $cr) {
                    if ($cr->$key == $value)
                        echo 'selected 3';
                }
            }
        }
    }

    /**
     * Function that sets "Alle" if no database records found
     *
     * @param object array $userValues
     * @param string $value
     * @return bool $hasRecords
     */
    static function countComplexMultipleSelect($collection, $relationship, $oneLessForeach = false)
    {
        $hasRecords = false;
        if ($oneLessForeach == false) {
            if (count($collection))
                foreach ($collection as $col) {
                    if (count($col->$relationship) > 0) {
                        $hasRecords = true;
                    }
                }
        } else {
            if (count($collection->$relationship) > 0) {

                $hasRecords = true;

            }
        }
        return $hasRecords;
    }

    /**
     * Echo required font awesome asterisk
     *
     * @echo string
     */
    static function asterisk()
    {
        echo '<i class="fa fa-asterisk text-info"></i>';
    }

    /**
     * Echo required font awesome asterisk
     *
     * @echo string
     */
    static function incrementCounter($counter)
    {
        return $counter++;
    }

    /**
     * Highlight keywords in string
     *
     * @param string $needle
     * @param string $haystack
     * @return string $newstring
     */
    static function highlightKeyword($needle, $haystack)
    {
        $occurrences = substr_count(strtolower($haystack), strtolower($needle));
        $newstring = $haystack;
        $match = array();

        for ($i = 0; $i < $occurrences; $i++) {
            $match[$i] = stripos($haystack, $needle, $i);
            $match[$i] = substr($haystack, $match[$i], strlen($needle));
            $newstring = str_replace($match[$i], '[#]' . $match[$i] . '[@]', strip_tags($newstring));
        }

        $newstring = str_replace('[#]', '<span class="highlight">', $newstring);
        $newstring = str_replace('[@]', '</span>', $newstring);
        return $newstring;
    }

    /**
     * Highlight keywords in string
     *
     * @param string $needles
     * @param string $haystack
     * @return string $newstring
     */
    static function highlightKeywords($needles = array(), $haystack)
    {
        $parsedText = ViewHelper::replaceHtmlTags($haystack);

        foreach ($needles as $keyword) {
            if (stripos($parsedText, $keyword) !== false) {
                $parsedText = preg_replace("/$keyword/i", "<span class='highlight'>\$0</span>", $parsedText);
            }
        }
        return $parsedText;
    }

    /**
     * Swap all html tags with blank or desired string
     *
     * @param string $html
     * @param string $replacement
     * @return string
     */
    static function replaceHtmlTags($html, $replacement = ' ')
    {
        return preg_replace('#<[^>]+>#', $replacement, $html);
    }

    /**
     * Return n number of sentences. Default2
     *
     * @param string $body
     * @param int $sentencesToDisplay
     * @return string $newstring
     */
    static function sentencesToDisplay($body, $sentencesToDisplay = 2)
    {
        $nakedBody = preg_replace('/\s+/', ' ', strip_tags($body));
        $sentences = preg_split('/(\.|\?|\!)(\s)/', $nakedBody);

        if (count($sentences) <= $sentencesToDisplay)
            return $nakedBody;

        $stopAt = 0;
        foreach ($sentences as $i => $sentence) {
            $stopAt += strlen($sentence);

            if ($i >= $sentencesToDisplay - 1)
                break;
        }

        $stopAt += ($sentencesToDisplay * 2);
        $newString = trim(substr($nakedBody, 0, $stopAt));

        return $newString;
    }


    /**
     * Return shortened text extract with keyword parameter
     *
     * @param string $needle
     * @param string $haystack
     * @return string $newstring
     */
    static function extractText($needle, $haystack)
    {
        if (empty($haystack)) return;
        $newstring = '';
        // $haystack = html_entity_decode(ViewHelper::replaceHtmlTags($haystack)); // JIRA Task NEPTUN-652
        $haystack = ViewHelper::replaceHtmlTags($haystack);
        $extractLenght = 128;
        $needlePosition = strpos($haystack, $needle);
        $newstring = '... ' . substr($haystack, $needlePosition, 128) . ' ...';
        return ViewHelper::replaceHtmlTags($newstring);
    }

    /**
     * Return shortened text extract
     *
     * @param string $haystack
     * @return string $newstring
     */
    static function extractTextSimple($haystack)
    {
        if (empty($haystack)) return;
        // $haystack = html_entity_decode(ViewHelper::replaceHtmlTags(trim($haystack)));
        $haystack = ViewHelper::replaceHtmlTags(trim($haystack)); // JIRA Task NEPTUN-652
        $extractLenght = 128;
        $needlePosition = 0;
        $newstring = substr($haystack, $needlePosition, 128) . ' ...';
        return ViewHelper::replaceHtmlTags($newstring);
    }

    /**
     * Show active/inactive user count
     *
     * @param array $usersActive
     * @param array $usersInactive
     * @return string $newstring
     */
    static function showUserCount($usersActive, $usersInactive)
    {
        $newString = view('partials.showUserCount', compact('usersActive', 'usersInactive'))->render();
        return $newString;
    }


    /**
     * Show history link if history is available user count
     *
     * @param Document $document
     * @return string $link
     */
    static function showHistoryLink($document)
    {
        if (ViewHelper::canViewHistory()) {
            if (PublishedDocument::where('document_group_id', $document->document_group_id)->count() > 1)
                return $link = url('dokumente/historie/' . $document->id);
            // return $link = '<a href="'. url('dokumente/historie/' . $document->id) .'" class="link history-link">'. trans('sucheForm.history-available') . '</a>';
        }
    }


    /**
     * Check if user is Struktur admin Dokumenten Verfasser,Rundschreiben Verfasser
     * @param Collection $document
     * @param int $uid (user id)
     * @return object
     */
    static function canCreateEditDoc()
    {
        $uid = Auth::user()->id;
        $mandantUsers = MandantUser::where('user_id', $uid)->get();

        foreach ($mandantUsers as $mu) {
            $userMandatRoles = MandantUserRole::where('mandant_user_id', $mu->id)->get();
            //  dd( $userMandatRoles );
            foreach ($userMandatRoles as $umr) {
                if ($umr->role_id == 1 || $umr->role_id == 11 || $umr->role_id == 13)
                    return true;
            }
        }
        return false;
    }

    /**
     * Check if user is Historien Leser
     * @return bool
     */
    static function canViewHistory()
    {
        $uid = Auth::user()->id;
        $mandantUsers = MandantUser::where('user_id', $uid)->get();
        foreach ($mandantUsers as $mu) {
            $userMandatRoles = MandantUserRole::where('mandant_user_id', $mu->id)->get();
            foreach ($userMandatRoles as $umr) {
                if ($umr->role_id == 14 || $umr->role_id == 1)
                    return true;
            }
        }
        return false;
    }

    /**
     * Check if is
     * @return bool
     * HINT same as universalHasPermission(15)
     */
    static function canViewWikiManagmentAdmin()
    {
        $uid = Auth::user()->id;
        $mandantUsers = MandantUser::where('user_id', $uid)->get();
        foreach ($mandantUsers as $mu) {
            $userMandatRoles = MandantUserRole::where('mandant_user_id', $mu->id)->get();
            foreach ($userMandatRoles as $umr) {
                if ($umr->role_id == 15 || $umr->role_id == 1)//wiki redaktur
                    return true;
            }
        }
        return false;
    }

    /**
     * Get available wiki categories for the current user
     * @params array $whereInArrayParams
     * @return bool
     */
    static function getWikiUserCategories($whereInArrayParams = array())
    {
        $uid = Auth::user()->id;
        $wiki = new \StdClass();
        $categoriesPluck = WikiCategoryUser::where('user_id', Auth::user()->id)->pluck('wiki_category_id')->toArray();
        // dd( WikiCategoryUser::where('user_id', Auth::user()->id)->get() );
        $userRoles = self::getUserRole($uid);
        $roles = self::getAvailableWikiCategories();
        $categoriesId = array_merge($categoriesPluck, $roles);
        
        // $categories = WikiCategory::whereIn('id', $categoriesId)->get();
        if (ViewHelper::universalHasPermission(array())) {
            $categoriesId = WikiCategory::pluck('id')->toArray();
        }

        $categories = WikiCategory::whereIn('id', $categoriesId);
        $wikies = WikiPage::whereIn('category_id', $categoriesId)->orderBy('updated_at', 'desc');

        $wiki->categoriesIdArray = $categoriesId;
        $wiki->categories = $categories;
        $wiki->pages = $wikies;

        return $wiki;
    }

    /**
     * Get Availabe Wiki
     * @return Mandant | bool
     */
    static function getAvailableWikiCategories()
    {
        $userRoles = self::getUserRole(Auth::user()->id);

        $wikiCatByRoles = WikiRole::whereIn('role_id', $userRoles)->pluck('wiki_category_id')->toArray();

        $wikiCategories = WikiCategory::where(function ($query) use ($wikiCatByRoles) {
            $query
                ->whereIn('id', $wikiCatByRoles)
                ->orWhere('all_roles', 1);
        })
            ->pluck('id')->toArray();
        return $wikiCategories;
    }

    /**
     * Get Availabe Wiki
     * @return Mandant | bool
     */
    static function wikiCanEditByCatId($category)
    {
        $userRoles = self::getUserRole(Auth::user()->id);

        $wikiCatByRoles = WikiRole::whereIn('role_id', $userRoles)->where('wiki_category_id', $category)->get();
        if (count($wikiCatByRoles) || self::universalHasPermission(array()))
            return true;
        return false;
    }

    /**
     * Generate comment boxes
     * @param Collection $collection
     * @param string $title
     * @return string $string (html template)
     */
    static function generateCommentBoxes($collection, $title, $withRow = false)
    {
        $string = '';
        $string = view('partials.comments', compact('collection', 'title', 'withRow'))->render();
        echo $string;
    }
    
    /**
     * Freigabe boxes
     * @param Collection $document
     * @return string $string (html template)
     */
    static function generateFreigabeBox($document)
    {
        $string = '';
        $string = view('partials.freigabeBox', compact('document'))->render();
        echo $string;
    }

    /**
     * Get all roles associated with the user
     * @param int $userId
     * @return array $roles
     */
    static function getUserRole($userId)
    {
        //fetch all mandant Users id's by $userId
        $mandantUserIds = MandantUser::where('user_id', $userId)->pluck('id')->toArray();
        $roles = MandantUserRole::whereIn('mandant_user_id', $mandantUserIds)->pluck('role_id')->toArray();
        return $roles;
    }

    /**
     * Get all user by role ids
     * @param array $roles
     * @return array $ids
     */
    static function getUsersByRole($roles)
    {
        $mandantUserRoles = MandantUserRole::whereIn('role_id', $roles)->pluck('mandant_user_id')->toArray();
        $mandantUserIds = MandantUser::whereIn('id', $mandantUserRoles)->pluck('user_id')->toArray();
        return $mandantUserIds;
    }

    /**
     * Universal user has permissions check
     * @param array $userArray
     * @return bool
     */
    static function universalHasPermission($userArray = array(), $withAdmin = true, $debug = false)
    {
        $uid = Auth::user()->id;

        $mandantUsers = MandantUser::where('user_id', $uid)->get();

        $hasPermission = false;
        foreach ($mandantUsers as $mu) {
            if ($mu->mandant->active == true) {  // if user mandant is active

                $userMandatRoles = MandantUserRole::where('mandant_user_id', $mu->id)->get();
                foreach ($userMandatRoles as $umr) {
                    if ($withAdmin == true) {
                        if ($umr->role_id == 1 || in_array($umr->role_id, $userArray)) {
                            $hasPermission = true;

                        }
                    } else {
                        if (in_array($umr->role_id, $userArray) == true && $umr->role_id != null) {
                            $hasPermission = true;
                        }
                    }
                }
            }

        }
        return $hasPermission;
    }

    /**
     * Universal document permission check
     * @param array $userArray
     * @param collection $document
     * @param bool $messagen
     * @return bool || response
     */
    static function getDocumentNewsData()
    {
        $news = DocumentType::find(1);//document type news
        return $news;
    }

    /**
     * Universal document permission check
     * @param array $userArray
     * @param collection $document
     * @param bool $message
     * @return bool || response
     */
    static function universalDocumentPermission($document, $message = true, $freigeber = false, $filterAuthors = false)
    {
        $uid = Auth::user()->id;
        $mandantUsers = MandantUser::where('user_id', $uid)->get();
        $role = 0;
        $hasPermission = false;

        foreach ($mandantUsers as $mu) {
            $userMandatRole = MandantUserRole::where('mandant_user_id', $mu->id)->first();
            if ($userMandatRole != null && $userMandatRole->role_id == 1)
                $hasPermission = true;

        }
        if ($freigeber == true) {
            $documentAprrovers = DocumentApproval::where('document_id', $document->id)->where('user_id', $uid)->get();
            if (count($documentAprrovers))
                $hasPermission = true;
        }
        $coAuthors = DocumentCoauthor::where('document_id', $document->id)->pluck('user_id')->toArray();

        if ($uid == $document->user_id || $uid == $document->owner_user_id || in_array($uid, $coAuthors)
            || ($freigeber == false && $filterAuthors == false && $document->approval_all_roles == 1) || $role == 1
        )
            $hasPermission = true;

        if ($message == true && $hasPermission == false)
            session()->flash('message', trans('documentForm.noPermission'));
        //if($document->id == 118)
        // dd($hasPermission);
        return $hasPermission;
    }

    /**
     * Document variant permission
     * @param collection $document
     * @return object $object
     */
    static function documentVariantPermission($document)
    {

        /*  class $object stores 2 attributes: 
            1. permissionExists( this is a global hasPermissionso we dont have to iterate again to see if permission exists  )
            2. variants (to store variants)[duuh]
        */

        $object = new \StdClass();
        $object->permissionExists = false;
        $mandantId = MandantUser::where('user_id', Auth::user()->id)->pluck('id');
        $mandantUserMandant = MandantUser::where('user_id', Auth::user()->id)->pluck('mandant_id');
        $mandantIdArr = $mandantUserMandant->toArray();
        $mandantRoles = MandantUserRole::whereIn('mandant_user_id', $mandantId)->pluck('role_id');
        $mandantRolesArr = $mandantRoles->toArray();

        $variants = EditorVariant::where('document_id', $document->id)->get();
        $hasPermission = false;

        foreach ($variants as $variant) {
            //  dd($variant);
            if ($hasPermission == false) {//check if hasPermission is already set

                if ($variant->approval_all_mandants == true) {//database check
                    if ($document->approval_all_roles == true) {//database check
                        $hasPermission = true;
                        $variant->hasPermission = true;
                        $object->permissionExists = true;
                    } else {
                        foreach ($variant->documentMandantRoles as $role) {// if not from database then iterate trough roles
                            if (self::universalDocumentPermission($document, false) == true) {
                                $hasPermission = true;
                                $variant->hasPermission = true;
                                $object->permissionExists = true;
                            } else {
                                if (in_array($role->role_id, $mandantRolesArr)) {//check if it exists in mandatRoleArr
                                    $variant->hasPermission = true;
                                    $hasPermission = true;
                                    $object->permissionExists = true;
                                }
                            }
                        }//end foreach documentMandantRoles
                    }
                } else {
                    foreach ($variant->documentMandantMandants as $mandant) {

                        if (self::universalDocumentPermission($document, false, false, true) == true) {
                            if (self::userHasMandantVariant($document) == 0) {
                                $hasPermission = true;
                                $variant->hasPermission = true;
                                $object->permissionExists = true;
                            } else {
                                if ($variant->id == self::userHasMandantVariant($document)) {
                                    $hasPermission = true;
                                    $variant->hasPermission = true;
                                    $object->permissionExists = true;
                                }
                            }

                            // dd('test');
                        } else if (in_array($mandant->mandant_id, $mandantIdArr)) {
                            // dd($variant->documentMandantMandants);
                            if ($document->approval_all_roles == true) {
                                $hasPermission = true;
                                $variant->hasPermission = true;
                                $object->permissionExists = true;
                            } else {
                                foreach ($variant->documentMandantRoles as $role) {
                                    if (in_array($role->role_id, $mandantRolesArr)) {
                                        $variant->hasPermission = true;
                                        $hasPermission = true;
                                        $object->permissionExists = true;
                                    }
                                }//end foreach documentMandantRoles
                            }
                        }
                    }//end foreach documentMandantMandants
                }
            }
        }
        if ($object->permissionExists == false && self::universalDocumentPermission($document, false, false, true) == true
            && count($variants)
        ) {
            $object->permissionExists = true;
            $variants[0]->hasPermission = true;
        }
        $object->variants = $variants;
        return $object;
    }//end documentVariant permission

    /**
     * Check if user has mandant variant
     * @param collection $document
     * @return int
     */
    static function userHasMandantVariant($document)
    {
        $uid = Auth::user()->id;
        $variants = EditorVariant::where('document_id', $document->id)->get();
        $mandantId = MandantUser::where('user_id', Auth::user()->id)->pluck('id');
        $mandantUserMandant = MandantUser::where('user_id', Auth::user()->id)->pluck('mandant_id');
        $mandantIdArr = $mandantUserMandant->toArray();
        $hasOwnMandant = 0;
        foreach ($variants as $variant) {
            foreach ($variant->documentMandantMandants as $mandant) {
                if (in_array($mandant->mandant_id, $mandantIdArr)) {
                    $hasOwnMandant = $variant->id;
                }
            }
        }
        return $hasOwnMandant;
    }


    /**
     * Document variant permission
     * @param collection $document
     * @return object $object
     */
    static function documentUsersPermission($document)
    {

        /*  class $object stores 2 attributes: 
            1. permissionExists( this is a global hasPermissionso we dont have to iterate again to see if permission exists  )
            2. variants (to store variants)[duuh]
        */

        $object = new \StdClass();
        $object->permissionExists = false;
        $mandantId = MandantUser::where('user_id', Auth::user()->id)->pluck('id');
        $mandantUserMandant = MandantUser::where('user_id', Auth::user()->id)->pluck('mandant_id');
        $mandantIdArr = $mandantId->toArray();
        $mandantRoles = MandantUserRole::whereIn('mandant_user_id', $mandantId)->pluck('role_id');
        $mandantRolesArr = $mandantRoles->toArray();

        $variants = EditorVariant::where('document_id', $document->id)->get();
        $hasPermission = false;


        foreach ($variants as $variant) {
            if ($hasPermission == false) {//check if hasPermission is already set
                if ($variant->approval_all_mandants == true) {//database check

                    if ($document->approval_all_roles == true) {//database check
                        $hasPermission = true;
                        $variant->hasPermission = true;
                        $object->permissionExists = true;
                    } else {
                        foreach ($variant->documentMandantRoles as $role) {// if not from database then iterate trough roles
                            if (in_array($role->role_id, $mandantRolesArr)) {//check if it exists in mandatRoleArr
                                $variant->hasPermission = true;
                                $hasPermission = true;
                                $object->permissionExists = true;
                            }
                        }//end foreach documentMandantRoles
                    }
                } else {
                    foreach ($variant->documentMandantMandants as $mandant) {
                        if ($this->universalDocumentPermission($document, false) == true) {
                            $hasPermission = true;
                            $variant->hasPermission = true;
                            $object->permissionExists = true;
                        } else if (in_array($mandant->mandant_id, $mandantIdArr)) {
                            if ($document->approval_all_roles == true) {
                                $hasPermission = true;
                                $variant->hasPermission = true;
                                $object->permissionExists = true;
                            } else {
                                foreach ($variant->documentMandantRoles as $role) {
                                    if (in_array($role->role_id, $mandantRolesArr)) {
                                        $variant->hasPermission = true;
                                        $hasPermission = true;
                                        $object->permissionExists = true;
                                    }
                                }//end foreach documentMandantRoles
                            }
                        }
                    }//end foreach documentMandantMandants
                }
            }
        }

        $object->variants = $variants;
        return $object;
    }//end documentVariant permission


    /**
     * Check if document has pdf (used for example show title etc)
     * @param collection $document
     * @return bool
     */
    static function hasPdf($document)
    {
        $hasPdf = false;
        foreach ($document->documentUploads as $k => $attachment) {
            if ($hasPdf == false) {
                $type = \File::extension(url('open/' . $document->id . '/' . $attachment->file_path));
                if (strtolower($type) == 'pdf')
                    $hasPdf = true;
            }
        }
        return $hasPdf;
    }
    
    /**
     * Check if file has extension
     * @param collection $document
     * @return bool
     */
    static function fileTypeAllowed($file,$exclude=array())
    {
       $allowedFileArray = ['txt','doc','docx','xls','xlsx','ppt','pptx','pdf'];//,'png','jpg','jpeg','gif'
       $extension = $file->getClientOriginalExtension();
            if (in_array($extension,$allowedFileArray) ) {
                return true;
            }
        return false;
    }

    /**
     * Get type of file
     * @param collection $document
     * @param collection $attachment
     * @return string || null
     */
    static function htmlObjectType($document, $attachment)
    {
        $type = \File::extension(url('open/' . $document->id . '/' . $attachment->file_path));
        $htmlObjectType = null;
        if (strtolower($type) == 'png' || strtolower($type) == 'jpg' || strtolower($type) == 'jpeg' || strtolower($type) == 'gif')
            $htmlObjectType = 'image/' . $type;
        elseif (strtolower($type) == 'pdf')
            $htmlObjectType = 'application/pdf';
        return $htmlObjectType;
    }


    /**
     * Get Mandants parent Mandant if he has one
     * @param Mandant $mandant
     * @return Mandant | bool
     */
    static function getHauptstelle($mandant)
    {
        $hauptstelleId = $mandant->mandant_id_hauptstelle;
        if ($hauptstelleId) {
            return Mandant::find($hauptstelleId);
        } else return false;
    }

    /**
     * Get User by ID
     * @param int $id
     * @return User | bool
     */
    static function getUser($id)
    {
        if ($user = User::find($id)) {
            return $user;
        } else return false;
    }

    /**
     * Get First Mandant by User ID
     * @param int $id
     * @return Mandant | bool
     */
    static function getMandant($id)
    {
        if ($mandantUser = MandantUser::where('user_id', $id)->first()) {
            return Mandant::find($mandantUser->mandant_id);
        } else return false;
    }

    /**
     * Get all mandants by User ID
     * @param int $id
     * @return Collection | bool
     */
    static function getUserMandants($id)
    {
        if ($mandantUsers = MandantUser::where('user_id', $id)->groupBy('mandant_id')->get()) {
            $mandants = array();
            foreach ($mandantUsers as $mandantUser) {
                $mandants[] = Mandant::find($mandantUser->mandant_id);
            }
            return collect($mandants);
        } else return false;
    }

    /**
     * Check if any users mandant has Neptun flag checked
     * @param int $id
     * @return bool
     */
    static function getMandantIsNeptun($id)
    {
        $mandantIds = MandantUser::where('user_id', $id)->get()->pluck('mandant_id');
        foreach (Mandant::whereIn('id', $mandantIds)->get() as $mandant) {
            if ($mandant->rights_admin) return true;
        }
    }


    /**
     * Get all user by passed role ID
     * @param int $roleId
     * @param int $userId
     * @return string $options
     */
    static function getUsersByInternalRole($roleId, $userId)
    {
        // dd($roleId.' '.$userId);
        $mandantUsersNeptun = array();
        $mandantUsers = MandantUser::all();
        $muAA = $mandantUsers->pluck('user_id')->toArray();

        //Order last name asc fix
        $userArray = User::whereIn('id', $muAA)->orderBy('last_name', 'asc')->pluck('id')->toArray();
        $orderString = '';
        foreach ($userArray as $ua) {
            $orderString .= ', ' . $ua;
        }

        $mandantUsers = MandantUser::orderByRaw(\DB::raw("FIELD(user_id " . $orderString . ")"))->get();
        // Get all users with telefonliste roles where mandant is with neptun flag
        foreach ($mandantUsers as $mandantUser) {
            foreach ($mandantUser->role as $role) {
                if ($role->phone_role && $mandantUser->mandant->rights_admin && $role->id == $roleId) {
                    if (!in_array($mandantUser, $mandantUsersNeptun))
                        array_push($mandantUsersNeptun, $mandantUser);
                }
            }
        }

        $html = '';

        if ($mandantUsersNeptun) {
            foreach ($mandantUsersNeptun as $mandantUser) {
                ($userId == $mandantUser->user->id) ? $selected = "selected" : $selected = "";
                $html .= '<option value="' . $mandantUser->user->id . '" data-mandant="' . $mandantUser->mandant->id . '" ' . $selected . '>';
                $html .= $mandantUser->user->last_name . ' ' . $mandantUser->user->first_name;
                $html .= ' [' . $mandantUser->mandant->mandant_number . ' - ' . $mandantUser->mandant->kurzname . ']';
                $html .= '</option>';
            }
        }

        return $html;
        // dd($html);
    }

    /**
     * Get Mandant Roles from MandantUserRole and Mandant
     * @param MandantUserRole $object
     * @param Mandant $object
     * @return Mandant | bool
     */
    static function getMandantRoles($mandantUserRole, $mandant)
    {
        //
    }

    /**
     * Check if Mandant has Wiki permission
     * @return bool
     */
    static function getMandantWikiPermission()
    {
        $user = Auth::user();
        $mandantUser = MandantUser::where('user_id', $user->id)->first();
        /*Neptun-500 remove wiki permissions from mandants and leave only user wiki permissions*/
        return false;
        /* End Neptun-500 remove wiki permissions from mandants and leave only user wiki permissions */


        if (isset($mandantUser->mandant))
            return (bool)$mandantUser->mandant->rights_wiki;
        else return false;
    }

    /**
     * Is Freigeber for the seleted document function
     * @param collection $document
     * @return bool
     */
    static function isThisDocumentFreigeber($document)
    {
        $uid = Auth::user()->id;
        $approval = DocumentApproval::where('document_id', $document->id)->where('user_id', $uid)->get();
        if (count($approval))
            return true;
        return false;

    }

    /**
     * Check if user has any visible roles, return true if so, else return false
     * @return bool
     */
    static function phonelistVisibility($user, $mandant)
    {
        $rolesCount = 0;
        foreach ($user->mandantRoles as $mandantUserRole) {
            if (ViewHelper::getMandant(Auth::user()->id)->rights_admin || ViewHelper::universalHasPermission()) {
                if ($mandantUserRole->role->phone_role || $mandantUserRole->role->mandant_role) {
                    if (!in_array($mandantUserRole->role->id, array_pluck($mandant->usersInternal, 'role_id')))
                        $rolesCount += 1;
                }
            } else {
                if ($mandant->rights_admin) {
                    if ($mandantUserRole->role->phone_role) {
                        if (!in_array($mandantUserRole->role->id, array_pluck($mandant->usersInternal, 'role_id')))
                            $rolesCount += 1;
                    }
                } else {
                    if ($mandantUserRole->role->mandant_role) {
                        if (!in_array($mandantUserRole->role->id, array_pluck($mandant->usersInternal, 'role_id')))
                            $rolesCount += 1;
                    }
                }
            }
        }

        return $rolesCount;
    }

    /**
     * detect if model is dirty or not
     * @return bool
     */
    static function isDirty($model, $dirty = false)
    {
        if ($model->isDirty() || $dirty == true)
            return true;
        return false;

    }

    /**
     * Process files for upload
     *
     * @param DB Object(collection) $model
     * @param string $path
     * @param array $files
     * @return \Illuminate\Http\Response
     */
    static function fileUpload($model, $path, $files, $sizeLimit = false)
    {
        $folder = $path . str_slug($model->id);
        $uploadedNames = array();
        if (!\File::exists($folder)) {
            \File::makeDirectory($folder, $mod = 0777, true, true);
        }
        
        // File size validation
        if($sizeLimit){
            $totalSize = 0;
            if (is_array($files)) {
                foreach ($files as $tmp) {
                    if (is_array($tmp)){
                        foreach ($tmp as $t) {
                            $totalSize += \File::size($t);
                        }
                    } else {
                        $totalSize += \File::size($tmp);
                    }
                }
            } 
            
            if($totalSize > $sizeLimit)
                return false; 
        }
        
        // Move uploaded files
        if (is_array($files)) {
            $uploadedNames = array();
            $counter = 0;
            foreach ($files as $file) {
                if (is_array($file)) {

                    foreach ($file as $f) {
                        $counter++;
                        if ($f !== NULL)
                            $uploadedNames[] = ViewHelper::moveUploaded($f, $folder, $model, $counter);
                    }
                } else
                    $uploadedNames[] = ViewHelper::moveUploaded($file, $folder, $model);
            }
        } else
            $uploadedNames[] = ViewHelper::moveUploaded($files, $folder, $model);
        return $uploadedNames;
    }

    /**
     * Move files from temp folder and rename them
     *
     * @param file object $file
     * @param string $folder
     * @param DB object(collection) $model
     * @return string $newName
     */
    static function moveUploaded($file, $folder, $model, $counter = 0)
    {
        $diffMarker = time() + $counter;
        $newName = str_slug($model->id) . '-' . date("d-m-Y-H:i:s") . '-' . $diffMarker . '.' . $file->getClientOriginalExtension();
        $filename = $file->getClientOriginalName();
        $uploadSuccess = $file->move($folder, $newName);
        \File::delete($folder . '/' . $filename);
        return $newName;
    }
    
    /**
     * generate edit inventory modal
     *
     * @param collection $item
     * @return template
     */
    static function generateInventoryEditModal($item){
        $categories = InventoryCategory::all();
        $sizes = InventorySize::all();
        return view('inventarliste.partials.editModal',compact('item', 'categories', 'sizes'))->render();
    }
    
    /**
     * generate Inventory view modal
     * @param collection $item
     * @return template
     */
    static function generateInventoryViewModal($item){
        $categories = InventoryCategory::all();
        $sizes = InventorySize::all();
        $mandants = Mandant::all();
        return view('inventarliste.partials.inventoryViewModal',compact('item', 'categories', 'sizes','mandants'))->render();
    }
    
    /**
     * generate inventory taken modal
     *
     * @param collection $item
     * @return template
     */
    static function generateInventoryTakenModal($item){
        $categories = InventoryCategory::all();
        $sizes = InventorySize::all();
        $mandants = Mandant::all();
        return view('inventarliste.partials.takenModal',compact('item', 'categories', 'sizes','mandants'))->render();
    }
    
    /**
     * generate history inventory modal
     *
     * @param collection $item
     * @return template
     */
    static function generateInventoryHistoryModal($item){
        return view('inventarliste.partials.historyModal',compact('item'))->render();
    }
    
    /**
     * inventory history modal comma space fix
     *
     * @param collection $history
     * @return string $string
     */
    static function genterateHistoryModalString($history){
        $string = $history->user->first_name.' '.$history->user->last_name;
        $tableFielsArray = ['inventory_category_id','inventory_size_id','value','text','mandant_id'];
        $countAffectedRows = 0;
        foreach($tableFielsArray as $field){
            if( $history->$field != null){
                $countAffectedRows++;
            }
        }
        if( $history->is_updated != null && ( ($countAffectedRows == 1 && $history->value == null ) || $countAffectedRows > 1) ){
            $string .= ', '.trans('inventoryList.itemUpdated');
        }
        if( !empty($history->description_text) ){
             $string .= ', '.$history->description_text;
        }
        else{
            if( $history->inventory_category_id != null ){
                $string .= ', '.trans('inventoryList.category').': '.$history->category->name;
            }
            
            if( $history->inventory_size_id != null ){
                $string .= ', '.trans('inventoryList.size').': '.$history->size->name;
            }
           
            if( $history->value != null ){
                if( $history->is_updated == null && $countAffectedRows == 1){
                    $string .= ', '.trans('inventoryList.itemTaken').': '.$history->value;
                }
                elseif( $history->is_updated == null && ($history->mandant_id != null || $history->text) ){
                     $string .= ', '.trans('inventoryList.itemTaken').': '.$history->value;
                }
                elseif( $history->is_updated != null && $countAffectedRows == 1){
                     $string .= ', '.trans('inventoryList.valueUpdated').': '.$history->value;
                }
                else{
                    $string .= ', '.trans('inventoryList.number').': '.$history->value;
                }
            }
           
            if( $history->min_stock != null ){
                $string .= ', '.trans('inventoryList.minStock').': '.$history->min_stock;
            }
            if( $history->purchase_price != null ){
                $string .= ', '.trans('inventoryList.purchasePrice').': '.$history->purchase_price;
            }
            if( $history->sell_price != null ){
                $string .= ', '.trans('inventoryList.sellPrice').': '.$history->sell_price;
            }
            if( $history->mandant_id != null ){
                $string .= ', '.trans('inventoryList.mandant').': '.$history->mandant->name;
            }
            if( $history->text != null ){
                $string .= ', '.trans('inventoryList.text').': '.$history->text;
            }
            if( ($history->neptun_intern == null || $history->neptun_intern == 0) && $history->is_updated != null ){
                $string .= ', '.trans('inventoryList.neptunInternShort').': Nein';
            }
            elseif($history->is_updated != null && $history->neptun_intern == 1){
                $string .= ', '.trans('inventoryList.neptunInternShort').': Ja';
            }
        }
        
        $string .= ', '.$history->created_at;
        
        return $string;
    }
    
    /**
     * Return list of search suggestions (mandants, users) for use in search suggestions on telephone list
     *
     * @return array $searchSuggestions
     */
    static function getTelephonelistSearchSuggestions(){
        
        $myMandant = MandantUser::where('user_id', Auth::user()->id)->first()->mandant;
        $myMandants = Mandant::whereIn('id', array_pluck(MandantUser::where('user_id', Auth::user()->id)->get(),'mandant_id'))->get();

        if(ViewHelper::universalHasPermission() || $myMandant->id == 1 || $myMandant->rights_admin == 1)
            $mandants = Mandant::where('active', 1)->orderBy('mandant_number')->get();
        else {
            $mandants = Mandant::where('active', 1)->where('rights_admin', 1)->orderBy('mandant_number')->get();
        }
        
        foreach($myMandants as $tmp){
            if(!$mandants->contains($tmp))
                $mandants->prepend($tmp);
        }
        
        // Sort by Mandant No.
        $mandants = array_values(array_sort($mandants, function ($value) {
            return $value['mandant_number'];
        }));

        foreach($mandants as $k => $mandant){
            
            $userArr = array();
            $usersInternal = array();

            $internalMandantUsers = InternalMandantUser::whereIn('mandant_id', array_pluck($myMandants, 'id'))
                ->where('mandant_id_edit', $mandant->id)->groupBy('role_id','user_id','mandant_id_edit')->get();
                
            foreach ($internalMandantUsers as $user){
                $usersInternal[] = $user;
            }
            
            foreach($mandant->users as $k2 => $mUser){
                
                foreach($mUser->mandantRoles as $mr){
                    // do not add the user if he is in $usersInternal array
                    if($mUser->active && !in_array($mUser->id, array_pluck($usersInternal,'user_id')) ){
                        // Check for phone roles
                        if( $mr->role->phone_role || $mr->role->mandant_role ) {
                            $internalRole = InternalMandantUser::where('role_id', $mr->role->id)
                                ->whereIn('mandant_id', array_pluck($myMandants, 'id'))->where('mandant_id_edit', $mandant->id)
                                ->groupBy('role_id','user_id','mandant_id_edit')->get();
                            // $internalRole = InternalMandantUser::where('role_id', $mr->role->id)->where('mandant_id_edit', $mandant->id)->first();
                            if(!count($internalRole)){
                                $userArr[] = $mandant->users[$k2]->id;
                            }
                        }
                    }
                }

            } // end second foreach
            
            // if($mandant->id == 1) dd($usersInternal);
            
            $mandant->usersInternal = $usersInternal;
            $mandant->usersInMandants = $mandant->users->whereIn('id',$userArr);
            
        }
        
        // Search suggestion array for telephone list select box
        $searchSuggestions = array();
        foreach ($mandants as $m) {
            $searchSuggestions[] = $m->mandant_number;
            $searchSuggestions[] = $m->kurzname;
            foreach ($m->usersInternal as $ui) {
                $searchSuggestions[] = $ui->user->first_name;
                $searchSuggestions[] = $ui->user->last_name;
            }
            foreach ($m->usersInMandants as $um) {
                $searchSuggestions[] = $um->first_name;
                $searchSuggestions[] = $um->last_name;
            }
        }
        $searchSuggestions = array_unique($searchSuggestions);
        natcasesort($searchSuggestions);
        // dd($searchSuggestions);
        
        return $searchSuggestions;
    }
    
    /**
     * Populate favorite categories for the logged user with existing document types
     *
     * @return null
     */
    static function setFavoriteCategories(){
        $documentTypes = DocumentType::all();
        foreach($documentTypes as $dt){
            $favCat = FavoriteCategory::where('name', $dt->name)->where('user_id', Auth::user()->id)->first();
            if(isset($favCat) == false) FavoriteCategory::create(['name' => $dt->name, 'user_id' => Auth::user()->id]);
        }
    }

}