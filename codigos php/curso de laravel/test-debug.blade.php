<!DOCTYPE html>
<html>
<head>
    <title>Debug</title>
</head>
<body>
    <h1>Debug do valor:</h1>
    <p>Valor da série: "{{ $serie->nome ?? 'SÉRIE NÃO ENCONTRADA' }}"</p>
    <p>Tipo: {{ gettype($serie->nome ?? 'string') }}</p>
    
    <form>
        <input type="text" value="{{ $serie->nome ?? '' }}">
    </form>
</body>
</html>