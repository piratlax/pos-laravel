<?php

namespace App\Http\Livewire;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithFileUploads;
	use WithPagination;



	public $name, $search, $image, $selected_id, $pageTitle, $componentName;
	private $pagination = 5;

	
	public function mount()
	{
		$this->pageTitle = 'Listado';
		$this->componentName = 'Categorías';
	
	}

	public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
	{
		if(strlen($this->search) > 0)
			$data = Category::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);
		else
			$data = Category::orderBy('id','desc')->paginate($this->pagination);



		return view('livewire.category.categories', ['categories' => $data])
		->extends('layouts.theme.app')
		->section('content');
	}

	public function Edit($id)
	{
		$record = Category::find($id,['id','name','image']);
		$this->name = $record->name;
		$this->selected_id = $record->id;
		$this->image = null;

		$this->emit('show-modal', 'show modal');
	}
	
	public function Store()
	{
		$rules = [
			'name' => 'required | unique:categories|min:3'
		];
		$messages = [
			'name.required' => 'El nombre de la categoria es requerido',
			'name.unique' => 'Ya existe la categoria',
			'name.min' => 'La categoria deben tener minimo 3 caractetes'
		];

		$this->validate($rules, $messages);

		$category = Category::create([
			'name' => $this->name
		]);

		$customFileName;

		if($this->image)
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/categorias', $customFileName);
			$category->image = $customFileName;
			$category->save();
		}

		$this->resetUI();
		$this->emit('category-added', 'Categoria Registrada');
	}
	public function resetUI()
	{
	$this->name='';
	$this->image=null;
	$this->search='';
	$this->selected_id=0;
	}

	public function Update()
	{
		$rules = [
			'name' => "required|min:3|unique:categories,name,{$this->selected_id}"
		];

		$messages = [
			'name.required' => 'Nombre de categoria requerido',
			'name.min' => 'El nombre de la categoria debe tener minimo 3 letras',
			'name.unique' => 'El nombre de la categoria ya existe'
		];

		$this->validate($rules, $messages);

		$category = Category::find($this->selected_id);
		$category->update([
			'name' => $this->name
		]);

		if ($this->image)
			{
				// Generar un nombre de archivo único
				$customFileName = uniqid() . '_.' . $this->image->extension();
				
				// Almacenar la nueva imagen
				$this->image->storeAs('public/categorias', $customFileName);
				
				// Obtener el nombre de la imagen anterior
				$imageName = $category->image;
				
				// Actualizar el nombre de la imagen en la base de datos
				$category->image = $customFileName;
				$category->save();

				// Verificar si existe una imagen anterior y eliminarla
				if ($imageName != null)
				{
					$filePath = 'storage/categorias/' . $imageName;
					if (file_exists($filePath))
					{
						unlink($filePath);
					}
				}
			}

		$this->resetUI();
		$this->emit('category-updated', 'Categoria actualizada');
	}


	
	protected $listeners = ['deleteRow' => 'destroy'];

public function destroy($id)
{
    $category = Category::find($id);
    if ($category) {
        $imageName = $category->image;
        $category->delete();

        if ($imageName != null && file_exists('storage/categories/' . $imageName)) {
            unlink('storage/categories/' . $imageName);
        }

        $this->resetUI();
        $this->emit('category-deleted', 'Categoría Eliminada');
    } else {
        $this->emit('category-error', 'Categoría no encontrada');
    }
}

}
