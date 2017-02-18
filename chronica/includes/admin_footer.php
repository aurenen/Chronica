    </div>
</div>
<div id="footer">
    <p>&copy; <?php poweredBy('link'); ?> 2016.</p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="js/flatpickr.min.js"></script>
<script src="js/simplemde.min.js"></script>
<script>
    $(document).ready(function(){
      $(".date").flatpickr();
    });

    var simplemde = new SimpleMDE({
      autofocus: true,
      element: $("#md")[0],
      forceSync: true,
      indentWithTabs: false,
      promptURLs: true,
      renderingConfig: {
          codeSyntaxHighlighting: false,
      }
  });
</script>
</body>
</html>