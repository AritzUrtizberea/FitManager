@extends('layouts.admin')

@section('content')

<div class="flex justify-between items-center mb-6 p-5 bg-white rounded-xl shadow-sm border border-gray-100">
    
    <div>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <span class="text-indigo-600 bg-indigo-50 p-2 rounded-lg">
                <i class="fas fa-box-open"></i>
            </span>
            Gestión de Productos
        </h1>
        <p class="text-gray-500 text-sm mt-2 ml-1">
            Administra el catálogo y las calorías.
        </p>
    </div>

    <a href="{{ route('admin.products.create') }}" 
       class="group flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition-all duration-200 transform hover:-translate-y-0.5"
       style="background-color: #059669; color: #ffffff; text-decoration: none;"> <div class="bg-emerald-500 p-1 rounded-full group-hover:bg-emerald-600 transition">
           <i class="fas fa-plus text-xs"></i>
       </div>
       <span>Nuevo Producto</span>
    </a>

</div>

<div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-100 border-b-2 border-gray-200">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-20">
                        ID
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Nombre
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Calorías
                    </th>
                    <th class="px-5 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition duration-150">
                    
                    <td class="px-5 py-4 text-sm text-gray-500 font-mono">
                        #{{ $product->id }}
                    </td>
                    
                    <td class="px-5 py-4 text-sm font-medium text-gray-900">
                        {{ $product->name }}
                    </td>

                    <td class="px-5 py-4 text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            <i class="fas fa-fire-alt mr-1"></i>
                            {{ $product->calories ?? 0 }} kcal
                        </span>
                    </td>

                    <td class="px-5 py-4 text-sm text-right whitespace-nowrap">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.products.edit', $product->id) }}" 
                               class="text-blue-600 hover:text-blue-900 p-2 hover:bg-blue-50 rounded transition" 
                               title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900 p-2 hover:bg-red-50 rounded transition"
                                        title="Eliminar"
                                        onclick="return confirm('¿Estás seguro de eliminar {{ $product->name }}?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                            <p class="text-lg">No hay productos registrados todavía.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-5 py-4 bg-white border-t border-gray-200">
        {{ $products->links() }}
    </div>
</div>

@endsection