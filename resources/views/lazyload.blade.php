<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Lazy Load') }}</title>
    <!-- cdnjs -->
    <script src="{{url('js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>
    <style>
        img.lazy {
        width: 700px; 
        height: 467px; 
        display: block;
    }
    </style>
</head>
<body>
    @foreach (App\VariantImages::all() as $item)
    
    
        <img class="lazy" data-src="{{ url('/variantimages/'.$item->image1) }}" />
        <br>

        
    @endforeach
</body>
<script type="text/javascript">

    $(function() {
        $('.lazy').lazy({
		  removeAttribute: false,
		  afterLoad: function(element) {
              console.log(element+' loaded successfully !');
		  }
	  });
    });

</script>
</html>