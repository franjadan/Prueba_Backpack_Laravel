<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {

        if(backpack_auth()->user()->role != 1){
            $this->crud->denyAccess(['list','create', 'edit','delete']);
        }

        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');

        $this->crud->addClause('where','id', '<>', backpack_auth()->user()->id);
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
            'name' => 'name', 
            'label' => "Nombre",
            'type' => 'Text'
        ]);
        CRUD::addColumn([
            'name' => 'email', 
            'label' => "Email",
            'type' => 'Text'
        ]);        
        CRUD::addColumn([
            'name'    => 'role',
            'label'   => 'Rol',
            'type'    => 'select_from_array',
            'options' => [1 => 'Administrador', 2 => 'Usuario']
        ]);

        CRUD::addColumn([
            'name' => 'posts',
            'type' => 'relationship_count', 
            'label'=> 'Posts',   
            'suffix' => ' publicaciones'
        ]);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */

        $this->crud->addFilter([
            'type'  => 'text',
            'name'  => 'name',
            'label' => 'Nombre'
        ], 
        false,
        function($value) { 
            $this->crud->addClause('where', 'name', 'LIKE', "%$value%");
        });

        $this->crud->addFilter([
            'type'  => 'text',
            'name'  => 'email',
            'label' => 'Email'
        ],
        false,
        function($value) { 
            $this->crud->addClause('where', 'email', 'LIKE', "%$value%");
        });

        $this->crud->addFilter([
            'name'  => 'role',
            'type'  => 'dropdown',
            'label' => 'Rol'
          ], [
            1 => 'Administrador',
            2 => 'Usuario',
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
        CRUD::setValidation(UserRequest::class);

        CRUD::addField([
            'name'  => 'name',
            'label' => "Nombre",
            'type'  => 'text',
        ]);
        CRUD::addField([
            'name'  => 'email',
            'label' => "Email",
            'type'  => 'email',
        ]);        
        
        CRUD::addField([
            'name'  => 'role',
            'label' => "Selecciona el rol del usuario",
            'type'  => 'select_from_array',
            'options' => [1 => 'Administrador', 2 => 'Usuario'],
            'allows_null' => false,
            'default' => 2,
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
