@extends('master')
    @section('content')
    <h1 class="text-primary">
        {{  ucfirst( trans('controller.administration') ) }}
    </h1>
    {!! Form::open([
           'url' => 'some action',
           'method' => 'POST',
           'class' => 'horizontal-form' ]) !!}
           
<!-- input box-->
<div class="col-lg-6"> 
    <div class="form-group">
        {!! ViewHelper::setInput('search','',old('search'),trans('mandantenForm.search') , 
               trans('mandantenForm.search') , true  ) !!}
    </div>   
</div><!--End input box-->
<!-- input box-->
<div class="col-lg-6"> 
    <div class="form-group">
        {!! ViewHelper::setCheckbox('deleted_users','',old('deleted_users'),trans('mandantenForm.showDeletedUsers') ) !!}
        
        {!! ViewHelper::setCheckbox('deleted_clients','',old('deleted_clients'),trans('mandantenForm.showDeletedClients') ) !!}
    </div>   
</div><!--End input box-->

    <div class="clearfix"></div>

<!-- button div-->    
<div class="col-md-3">
    <div class="form-wrapper">
        <button type="submit" class="btn btn-primary">search-trans</button>
        <button type="reset" class="btn btn-warning">reset-trans</button>
    </div>
</div><!-- End button div-->    
           
</form>
    <div class="clearfix"></div>
    
    <h2>Ausgabe Übersicht -trans</h2>
    
    <div class="panel-group" id="accordion">
    <div class="panel panel-primary" id="panel1">
        <div class="panel-heading">
             <h4 class="panel-title">
        <a data-toggle="collapse" data-target="#collapseOne" class="collapsed" 
           href="#collapseOne">
          Mandant ( 10 users)
        </a>
        <span class="pull-right">
            <button class="btn btn-default"> bearbeiten </button> 
            <button class="btn btn-default"> löchen </button>
            <button class="btn btn-default"> aktiv </button>
        </span>
      </h4>

        </div>
        <div id="collapseOne" class="panel-collapse collapse ">
            <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                        <th>Username</th>
                        <th class="col-md-8">Roles</th>
                        <th>Mandanten</th>
                        <th>Aktiv</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </thead>
                    <tbody>
                        @for( $i=0;$i<=10;$i++)
                            <tr>
                               <td class="valign">Herr John Doe{{$i}}</td>
                                <td class="col-md-8">
                                  <select name="role_id" class="form-control select col-md-8" data-placeholder="{{ trans('benutzerForm.roles') }}" multiple>
                                    <option value="1">Rolle 1</option>
                                    <option value="2">Rolle 2</option>
                                    <option value="3">Rolle 3</option>
                                    <option value="4">Rolle 4</option>
                                </select>
                                </td>
                                <td class="text-center valign">2</td>
                                <td class="valign"> @if($i%2 ==0) inaktiv @else aktiv @endif </td>
                                <td class="valign">
                                    <button class="btn btn-primary"><span class="fa fa-edit"></span> edit</button>
                                </td>
                                <td>
                                    <button class="btn btn-danger"><span class="fa fa-trash"></span> delete</button>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="panel panel-primary" id="panel2">
        <div class="panel-heading">
             <h4 class="panel-title">
        <a data-toggle="collapse" data-target="#collapseTwo" 
           href="#collapseTwo" class="collapsed">
              Neptun (4 users)
        </a>
      </h4>

        </div>
        <div id="collapseTwo" class="panel-collapse collapse">
            <div class="panel-body">
                 <table class="table table-hover">
                    <thead>
                        <th>Username</th>
                        <th class="col-md-8">Roles</th>
                        <th>Mandanten</th>
                        <th>Aktiv</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </thead>
                    <tbody>
                        @for( $i=0;$i<=4;$i++)
                            <tr>
                               <td class="valign">frau Johanna Doe{{$i}}</td>
                                <td class="col-md-8">
                                  <select name="role_id" class="form-control select col-md-8" data-placeholder="{{ trans('benutzerForm.roles') }}" multiple>
                                    <option value="1">Rolle 1</option>
                                    <option value="2">Rolle 2</option>
                                    <option value="3">Rolle 3</option>
                                    <option value="4">Rolle 4</option>
                                </select>
                                </td>
                                <td class="text-center valign">2</td>
                                <td class="valign"> @if($i%2 ==0) inaktiv @else aktiv @endif </td>
                                <td class="valign">
                                    <button class="btn btn-primary"><span class="fa fa-edit"></span> edit</button>
                                </td>
                                <td class="valign">
                                    <button class="btn btn-danger"><span class="fa fa-trash"></span> delete</button>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="panel panel-primary" id="panel2">
        <div class="panel-heading">
             <h4 class="panel-title">
        <a data-toggle="collapse" data-target="#collapseThree" class="collapsed" 
           href="#collapseThree">
          Mandant ( 2 users)
        </a>
        <span class="pull-right">
            <button class="btn btn-default"> bearbeiten </button> 
            <button class="btn btn-default"> löchen </button>
            <button class="btn btn-default"> aktiv </button>
        </span>
      </h4>

        </div>
        <div id="collapseThree" class="panel-collapse collapse">
            <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                        <th>Username</th>
                        <th class="col-md-8">Roles</th>
                        <th>Mandanten</th>
                        <th>Aktiv</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </thead>
                    <tbody>
                        @for( $i=0;$i<=3;$i++)
                            <tr>
                               <td class="valign">Herr John Doe{{$i}}</td>
                                <td class="col-md-8">
                                  <select name="role_id" class="form-control select col-md-8" data-placeholder="{{ trans('benutzerForm.roles') }}" multiple>
                                    <option value="1">Rolle 1</option>
                                    <option value="2">Rolle 2</option>
                                    <option value="3">Rolle 3</option>
                                    <option value="4">Rolle 4</option>
                                </select>
                                </td>
                                <td class="text-center valign">2</td>
                                <td class="valign"> @if($i%2 ==0) inaktiv @else aktiv @endif </td>
                                <td class="valign">
                                    <button class="btn btn-primary"><span class="fa fa-edit"></span> edit</button>
                                </td>
                                <td>
                                    <button class="btn btn-danger"><span class="fa fa-trash"></span> delete</button>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- End panel-->
</div>

    {!! Form::open([
           'url' => 'some action',
           'method' => 'POST',
           'class' => 'horizontal-form']) !!}
    </form>
    
    {!! Form::open([
           'url' => 'some action',
           'method' => 'POST',
           'class' => 'horizontal-form']) !!}
           
           Suche Ausgabe
           
    </form>
    
    
    @stop