</div><br><br>
<!--        footer-->
    <footer class="text-center" id="footer"> &copy; copyright 2017 Xploit Stores <br>
        Developed by Obalowu Sodiq
    </footer>
  <script>
    function updateSize(){
      var sizestring = '';
      for(var i = 1; i <= 12; i++){
        if(jQuery('#size'+i).val() != ''){
          sizestring += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+','  ;
        }
      }
      jQuery('#sizes').val(sizestring);
    }

    function get_child_option(selected){
      if (typeof selected === 'undefined' ) {
        var selected = '';
      }
      var parentID = jQuery('#parent').val();
      jQuery.ajax({
        url : '/ecommerce/admin/parse/child_category.php',
        type : 'POST',
        data : {parentID : parentID, selected : selected},
        success:function(data){
          jQuery('#child').html(data);
        },
        error: function(){alert("Something went wrong")},
      });
    }
    jQuery('select[name = "parent"]').change(function(){
      get_child_option();
    });

  </script>
	  </body>
</html>
