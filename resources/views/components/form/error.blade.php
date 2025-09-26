@props(['name'])

@error(htmlFormNotationToDot($name))
    <div class="mt-1 text-sm text-red-600 rounded">
        {{ $message }}
    </div>
@enderror
