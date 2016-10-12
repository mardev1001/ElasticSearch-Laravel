<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Dev-Tools</title>
    <style type="text/css">
        html { font-family: Arial, sans-serif; }
    </style>
</head>
<body>
    
    <h1>Document Status Update</h1>
    <div class="results">
        <h3>Summary</h3>
        <div class="results-output">
            <strong>Documents updated: {{count($documentsUpdated)}} </strong> <hr>
            
            @foreach($documentsUpdated as $document)
                #{{$document}} <br>
            @endforeach
            
        </div>
    </div>
    
</body>
</html>

