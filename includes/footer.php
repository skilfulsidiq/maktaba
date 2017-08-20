
</div><br><br>
<!--        footer-->
    <footer class="text-center" id="footer"> &copy; copyright 2017 Xploit Stores <br>
        Developed by Obalowu Sodiq
    </footer>
    <script>
       function detailsmodal(id){
            // alert (id);
        var data = {id : id};
        jQuery.ajax({
            url: '/ecommerce/includes/detailmodal.php',
            method : 'POST',
            data : data,
            success : function(data){
              jQuery('body').append(data);
              jQuery('#details-modal').modal('toggle');
                },
            error : function(){
              alert("Something went wrong");
                }

            });
        }
    </script>
	</body>
</html>
