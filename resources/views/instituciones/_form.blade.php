@csrf
<div class="space-y-4">
    <div>
        <x-input-label for="nombre" value="Nombre" />
        <x-text-input id="nombre" name="nombre" class="mt-1 block w-full" value="{{ old('nombre', $institucion->nombre ?? '') }}" required />
        <x-input-error :messages="$errors->get('nombre')" class="mt-1" />
    </div>
    <div>
        <x-input-label for="codigo" value="Código" />
        <x-text-input id="codigo" name="codigo" class="mt-1 block w-full" value="{{ old('codigo', $institucion->codigo ?? '') }}" required />
        <x-input-error :messages="$errors->get('codigo')" class="mt-1" />
    </div>
    <div>
        <x-input-label for="ciudad" value="Ciudad" />
        <x-text-input id="ciudad" name="ciudad" class="mt-1 block w-full" value="{{ old('ciudad', $institucion->ciudad ?? '') }}" />
    </div>
    <div>
        <x-input-label for="contacto_email" value="Correo de contacto" />
        <x-text-input id="contacto_email" type="email" name="contacto_email" class="mt-1 block w-full" value="{{ old('contacto_email', $institucion->contacto_email ?? '') }}" />
    </div>
    <div>
        <x-input-label for="contacto_telefono" value="Teléfono de contacto" />
        <x-text-input id="contacto_telefono" name="contacto_telefono" class="mt-1 block w-full" value="{{ old('contacto_telefono', $institucion->contacto_telefono ?? '') }}" />
    </div>
    <div class="flex items-center gap-2">
        <input type="checkbox" id="activa" name="activa" value="1" {{ old('activa', $institucion->activa ?? true) ? 'checked' : '' }}>
        <x-input-label for="activa" value="Institución activa" />
    </div>
</div>
