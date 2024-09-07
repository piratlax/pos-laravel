
    <div class="col-sm-12">
        <div class="widget-heading d-flex justify-content-between align-items-center">
            <h4 class="card-title">
                <b>{{$componentName}} | {{$pageTitle}}</b>
            </h4>
            <button type="button" class="btn ripple btn-primary px-2" data-bs-toggle="modal" data-bs-target="#theModal">Agregar</button>
        </div>
        
        @include('common.searchbox')
        
        <div class="widget-content">        
            <div class="table-responsive">
                <table class="table table-bordered table-striped mt-1">
                    <thead class="text-white" style="background: #3B3F5C">
                        <tr>
                            <th class="table-th text-white">DESCRIPCIÓN</th>
                            <th class="table-th text-white text-center">IMAGEN</th>
                            <th class="table-th text-white text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td><h6>{{$category->name}}</h6></td>
                            <td class="text-center">
                                <span>
                                    <img src="{{ asset('storage/categorias/' . $category->imagen) }}" alt="imagen de ejemplo" height="70" width="80" class="rounded">
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="javascript:void(0)" 
                                   wire:click="Edit({{$category->id}})"
                                   class="btn btn-dark mtmobile" title="Edit">
                                   <i class="lni lni-pencil"></i>
                                </a>
                                
                                <a href="javascript:void(0)"
									onclick="Confirm('{{$category->id}}', '{{ $category->products->count()}}')" 
                                   class="btn btn-dark" title="Delete">
                                   <i class="lni lni-trash"></i>
                                </a>
							

{{-- Si tiene productos relacionados no los muestra usando la funcion en el modelo --}}

								{{-- @if ({{ $category->products->count()}} ><1)

                                <a href="javascript:void(0)"
									onclick="Confirm('{{$category->id}}')" 
                                   class="btn btn-dark" title="Delete">
                                   <i class="lni lni-trash"></i>
                                </a>
								@endif --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$categories->links()}}
            </div>
        </div>
    

    @include('livewire.category.form')
	<script>
		document.addEventListener('DOMContentLoaded', function(){
	
			window.livewire.on('show-modal', msg =>{
				$('#theModal').modal('show')
			});

			window.livewire.on('category-added', msg =>{
				$('#theModal').modal('hide')
			});
			window.livewire.on('category-updated', msg =>{
				$('#theModal').modal('hide')
			});
	
	
		});
	
	
	
		function Confirm(id, products) 
		{
			if (products > 0 )
		{
			swal('No se puede eliminar la categoria por que tiene productos relacionados')
			return;
		}
			swal({
			title: 'CONFIRMAR',
			text: '¿CONFIRMAS ELIMINAR EL REGISTRO?',
			type: 'warning',
			showCancelButton: true,
			cancelButtonText: 'Cerrar',
			cancelButtonColor: '#fff',
			confirmButtonColor: '#3B3F5C',
			confirmButtonText: 'Aceptar'
		}).then(function(result) {
			if(result.value){
				window.livewire.emit('deleteRow', id)
				swal.close()
			}

		})
}

	</script>
</div>


