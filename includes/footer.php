</div>

<footer class="text-center" id="footer">&copy; Copyright 2017 One Stop Pet Shop</footer>



	<script>
		$(window).scroll(()=> {
			var vscroll = $(this).scrollTop();
			$('#logotext').css({
				"transform" : "translate(0px, " + vscroll/1.5 +"px)"
			})
			$('#fore-flower').css({
				"transform" : "translate(0px, " + vscroll/12 +"px)"
			})
			$('#back-flower').css({
				"transform" : "translate(0px, " + vscroll/12 +"px)"
			})
		})

		function detailsModal(id) {
			var data = {"id" : id};
			$.ajax({
				url : '/ecommerce/includes/detailsmodal.php',
				data : data,
				method : 'post',
				success : function(data) {
					$('body').append(data);
					$('#details-modal').modal('toggle');
				},
				error : function() {
					alert("Something went wrong");
				}
			});
		}
	</script>
	</body>
</html>