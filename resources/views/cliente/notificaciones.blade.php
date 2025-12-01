@extends('layouts.client')

@section('title', 'Notificaciones - Nexora Bolivia')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-slate-900">Notificaciones</h1>
            <p class="mt-2 text-slate-600">Avisos importantes sobre sus reclamos y actualizaciones de cuenta.</p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <form action="{{ route('notificaciones.marcarTodas') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <i class="fas fa-check-double mr-2 text-slate-400"></i> Marcar todas como leídas
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-slate-200">
        <!-- Header del Card -->
        <div class="bg-slate-900 px-6 py-4 border-b border-slate-800">
            <h3 class="text-lg font-medium text-white flex items-center gap-2">
                <i class="fas fa-bell text-indigo-400"></i> Bandeja de Entrada
            </h3>
        </div>

        @if($notificaciones->isEmpty())
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <i class="far fa-bell-slash text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-slate-900">Sin notificaciones</h3>
                <p class="text-slate-500 mt-1">No tiene mensajes nuevos en este momento.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-slate-100">
                @foreach($notificaciones as $notificacion)
                    <li>
                        <div class="block hover:bg-slate-50 transition duration-150 ease-in-out">
                            <div class="px-6 py-5">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 mr-4">
                                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full 
                                                {{ $notificacion->leida ? 'bg-slate-100 text-slate-400' : 'bg-indigo-100 text-indigo-600' }}">
                                                <i class="fas fa-bell"></i>
                                            </span>
                                        </div>
                                        <p class="text-sm font-bold text-slate-900 truncate">
                                            {{ $notificacion->titulo ?? 'Aviso del Sistema' }}
                                        </p>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-bold rounded-full uppercase tracking-wide {{ $notificacion->leida ? 'bg-slate-100 text-slate-600' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $notificacion->leida ? 'Leída' : 'Nueva' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="ml-14">
                                    <p class="text-sm text-slate-600 leading-relaxed">
                                        {{ $notificacion->mensaje }}
                                    </p>
                                    <div class="mt-2 flex items-center text-xs text-slate-400">
                                        <i class="far fa-clock flex-shrink-0 mr-1.5"></i>
                                        <p>
                                            {{ $notificacion->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    
    <div class="mt-6">
        {{ $notificaciones->links() }}
    </div>
</div>
@endsection
