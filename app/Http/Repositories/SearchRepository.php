<?php
namespace App\Http\Repositories;
/**
 * User: Marijan
 * Date: 14.06.2016.
 * Time: 08:11
 */

use Auth;
use DB;

use Carbon\Carbon;

use App\Mandant;
use App\MandantUser;
use App\User;
use App\WikiPage;

class SearchRepository
{
    /**
     * Search Phone list
     *
     * @return object array $array
     */
     public function phonelistSearch($request ){
        
         $query = Mandant::where('active',1);
        if($request->has('deletedMandants') )
             $query = $query->withTrashed();
        
         if( $request->has('parameter') )
            $query->where('name','LIKE', '%'.$request->get('parameter').'%')->orWhere('mandant_number','=',$request->get('parameter') );
            
        $mandants = $query->orderBy('mandant_number')->get();   
            
            // dd($request->all());
        if( count($mandants) ){
            
            foreach($mandants as $mandant){
                $mandantQuery = $mandant->users();
                
               /* if( $request->has('parameter') )
                    $mandantQuery->where('first_name',$request->get('parameter') )->orWhere('last_name',$request->get('parameter') );*/
               
                if($request->has('deletedMandants') )
                    $mandantQuery->withTrashed();
                
                $mandant->usersInMandants = $mandantQuery->get();
            }
        }
       else{
           
            // $mandants = Mandant::where('active', 1)->where('rights_admin', 1)->orderBy('mandant_number')->get();
            // $myMandant = MandantUser::where('user_id', Auth::user()->id)->first()->mandant;
            // if(!$mandants->contains($myMandant))
            //     $mandants->prepend($myMandant);
                
            $query = User::where('users.id','>',0);
                
            if( $request->has('parameter') )
                $query->where('first_name',$request->get('parameter') )->orWhere('last_name',$request->get('parameter') );
            
            if($request->has('deletedUsers') )
                $query ->withTrashed();        
             
            // $additionalQuery = $query;   
            $users = $query->get();   
            if( count($users) ){
                // DB::enableQueryLog();
                $usersIds = $query->pluck('id');   
                $userMandants = MandantUser::whereIn('user_id',$usersIds)->pluck('mandant_id');
                $mandants = Mandant::whereIn('id',$userMandants)->orderBy('mandant_number')->get();
                
                foreach($mandants as $mandant){
                    $userQuery = User::whereIn('id',$usersIds);
                     if( $request->has('parameter') )
                        $userQuery->where('first_name',$request->get('parameter') )->orWhere('last_name',$request->get('parameter') );
                     if($request->has('deletedUsers') )
                        $userQuery ->withTrashed();    
                
                    $mandant->usersInMandants = $userQuery->get();
                     
                    // dd($mandant->usersInMandants );
                      
                    if( count($mandant->usersInMandants) > 0 )
                        $mandant->openTreeView = true;
                }
                
            }
        }

        return $mandants;
    }
    /**
     * Search Wiki subject or inhalt
     *
     * @return object array $array
     */     
     public function searchWiki( $request ){
        $searchParam =  $request['search'];
        $results = WikiPage::where('name','LIKE','%'.$searchParam.'%' )->orWhere('subject','LIKE','%'.$searchParam.'%' )
        ->orWhere('content','LIKE','%'.$searchParam.'%' );
    //  	->paginate( 10, ['*'], 'suchergebnisse' );
         //->get() ;
         return $results;
         
     }
     
    /**
     * Search Wiki something
     *
     * @return object array $array
     */     
     public function searchManagmentSearch( $request ){
         $query = WikiPage::orderBy('created_at','desc');
         if( $request->admin != 1)
            $query = WikiPage::where()->orderBy('created_at','desc');
        //  dd($request);
         if( $request->name != '' )
             $query->where('name','like','%'.$request->name.'%');
         if( $request->subject != '' )
             $query->where('subject','like','%'.$request->subject.'%');
         
         if( $request->date_from != '' )
             $query->where('created_at','>', Carbon::parse($request->date_from) );
         
         if( $request->date_to != '' )
             $query->where('created_at','<', Carbon::parse($request->date_to));
         
         if( $request->category != '' )
             $query->where( 'category_id',$request->category );
             
         if( $request->status != '' )
             $query->where( 'status_id',$request->status );
         
         if( isset($request->ersteller) && $request->ersteller != '' )
             $query->where( 'user_id',$request->ersteller );
         
         $results = $query->get() ;
         
         return $results;
         
     }
}
