<!--$table->integer('document_type_id')->unsigned();//Fk
            $table->integer('user_id')->unsigned();//FK
            $table->timestamp('date_created');
            $table->integer('version');
            $table->string('name');
            $table->integer('owner_user_id')->unsigned();//FK
            $table->integer('document_status_id')->unsigned();//FK
            $table->string('search_tags');
            $table->text('summary');
            $table->timestamp('date_published');
            $table->timestamp('date_modified');
            $table->timestamp('date_expired');
            $table->integer('version_parent');
            $table->integer('document_group_id');
            $table->integer('iso_category_id')->unsigned();//FK
            $table->string('upload_filename');
            $table->boolean('show_name');
            $table->integer('adressat_id')->unsigned();//FK
            $table->string('betreff');
            $table->integer('document_replaced_id');
            $table->timestamp('date_approved');
            $table->boolean('email_approval');
            $table->boolean('approval_all_roles');
            $table->boolean('approval_all_mandants');
            $table->boolean('pdf_upload');-->

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        <label class=" control-label">{{ trans('label.name') }} <i class="fa fa-asterisk text-info"></i></label>
        <select class="form-control select" data-placeholder="{{trans('placeholders.documentStatus')}}">
             {!! ViewHelper::setSelect($documentStatus) !!}
        </select>
    </div>   
</div><!--End input box-->

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        <label class=" control-label">{{ trans('label.name') }} <i class="fa fa-asterisk text-info"></i></label>
        <input type="text" class="form-control" name="name" placeholder="{{ trans('label.name') }}" required 
		 @if( Request::is('*/edit') && $data->name )
			 value="{{ $data->name }}"
		 @else
		 	value="{{ old('name') }}"
		 @endif
		/>
    </div>   
</div><!--End input box-->
