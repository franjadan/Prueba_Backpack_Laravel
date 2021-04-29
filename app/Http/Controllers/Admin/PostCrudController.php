<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PostCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CloneOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkCloneOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {

        CRUD::setModel(\App\Models\Post::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/post');
        CRUD::setEntityNameStrings('post', 'posts');

        if(backpack_auth()->user()->role == 2){
            $this->crud->addClause('where','user_id',backpack_auth()->user()->id);
        }

        $this->crud->enableExportButtons();

    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name' => 'user',
            'type'  => 'relationship',
            'label' => 'Usuario', 
        ]);
        CRUD::addColumn([
            'name' => 'title', 
            'label' => "Título",
            'type' => 'Text'
        ]);        
        CRUD::addColumn([
            'name' => 'description', 
            'label' => "Descripción",
            'type' => 'Text',
            'escaped' => false
        ]); 

        CRUD::addColumn([
            'name' => 'comments',
            'type' => 'relationship_count', 
            'label'=> 'Comentarios',   
            'suffix' => ' comentarios',
            'link' => function($entry) {
                return backpack_url('comment');
            }
        ]);
        
        $this->crud->addFilter([
            'type'  => 'text',
            'name'  => 'name',
            'label' => 'Usuario'
        ], 
        false,
        function($value) { 
            $this->crud->addClause('join', 'users', function($query) use ($value) {
                $query->on('users.id','=','posts.user_id')->where('users.name', 'LIKE', "%$value%");
            });
        });

        $this->crud->addFilter([
            'type'  => 'text',
            'name'  => 'description',
            'label' => 'Descripcion'
        ], 
        false,
        function($value) { 
            $this->crud->addClause('where', 'description', 'LIKE', "%$value%");
        });

        $this->crud->addFilter([
            'type'  => 'text',
            'name'  => 'title',
            'label' => 'Título'
        ],
        false,
        function($value) { 
            $this->crud->addClause('where', 'title', 'LIKE', "%$value%");
        });

        $this->crud->addButtonFromModelFunction('line', 'post', 'openComments', 'beginning');

      
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);

        if(backpack_auth()->user()->role == 1){
            $this->crud->addColumn([
                'name' => 'user.name',
                'label' => 'Usuario',
                'type' => 'text',
            ]);
        }

        $this->crud->addColumn([
            'name' => 'title',
            'label' => 'Título',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => 'Descripción',
            'type' => 'text',
            'escaped' => false,
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PostRequest::class);


        if(backpack_auth()->user()->role == 1){
        CRUD::addField([
            'label' => "Usuario",
            'type' => 'select',
            'name' => 'user_id', 
            'options' => (function ($query) {
                return $query->where('id', '<>', backpack_auth()->user()->id)->get();
            })
        ]); 
        }else{
            CRUD::addField([
                'type' => 'hidden',
                'name' => 'user_id', 
                'value' => backpack_auth()->user()->id
            ]); 
        }      
        
        CRUD::addField([
            'name'  => 'title',
            'label' => "Título",
            'type'  => 'text',
        ]);        
        
        CRUD::addField([
            'name'  => 'description',
            'label' => 'Descripción',
            'type'  => 'ckeditor',
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
