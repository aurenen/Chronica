    </div>
</div>
<div id="footer">
    <p>&copy; <?php poweredBy('link'); ?> 2016.</p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="js/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/markdown-it/6.0.2/markdown-it.min.js"></script>
<script>
    var md = window.markdownit();  
    function generate() {
      var result = md.render($("#md").val());
      $("#preview,#html").html(result);
    }
    $(document).ready(function(){
      generate();
      $("#md").on("keyup", function (){
        generate();
      });
      $(".date").flatpickr();
    });
</script>
</body>
</html>