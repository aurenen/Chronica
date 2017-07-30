    </div>
</div>
<div id="footer">
    <p>&copy; <?php poweredBy('link'); echo " "; $y="2016"; echo (date('Y')==$y)?($y):($y."-".date('Y')); ?>.</p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="js/flatpickr.min.js"></script>
<script src="js/simplemde.min.js"></script>
<script src="js/select2.min.js"></script>
<script>
    $(document).ready(function(){
      $('.date').flatpickr();
      $('select').select2();

      $('#category_list').val( $('#category').val() );
      $('select#category').on('change', function() {
        $('#category_list').val( $('#category').val() );
      });
    });

<?php if ((is_current("add") || is_current("edit")) && is_markdown()): ?>
    var simplemde = new SimpleMDE({
      autofocus: true,
      element: $('#md')[0],
      forceSync: true,
      indentWithTabs: false,
      promptURLs: true,
      renderingConfig: {
          codeSyntaxHighlighting: false,
      }
  });
<?php endif; ?>
</script>
</body>
</html>