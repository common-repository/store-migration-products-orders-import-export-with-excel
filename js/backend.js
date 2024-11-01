(function( $ ) {

 		$("#eshopMigrationWooCommerce_signup").on('submit',function(e){
			e.preventDefault();	
			var dat = $(this).serialize();
			$.ajax({
				
				url:	"https://extend-wp.com/wp-json/signups/v2/post",
				data:  dat,
				type: 'POST',							
				beforeSend: function(data) {								
						console.log(dat);
				},					
				success: function(data){
					alert(data);
				},
				complete: function(data){
					dismiss();
				}				
			});	
		});
		

		function dismiss(){
			
				var ajax_options = {
					action: 'push_not',
					data: 'title=1',
					nonce: 'push_not',
					url: eshopMigrationWooCommerce.ajax_url,
				};			
				
				$.post( eshopMigrationWooCommerce.ajax_url, ajax_options, function(data) {
					$(".eshopMigrationWooCommerce_notification").fadeOut();
				});
				
				
		}
		
		$(".eshopMigrationWooCommerce_notification .dismiss").on('click',function(e){
				dismiss();
				console.log('clicked');
				
		});
		
	$(".proVersion").click(function(e){
		e.preventDefault();
		$("#eshopMigrationWooCommerceModal").slideDown();
	});

		$("#eshopMigrationWooCommerceModal .close").click(function(e){
			e.preventDefault();
			$("#eshopMigrationWooCommerceModal").fadeOut();
		});		

		var modal = document.getElementById('eshopMigrationWooCommerceModal');

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}
	
    $(function() {
        $('.eshopMigrationWooCommerce .color').wpColorPicker();
    });

	$(".eshopMigrationWooCommerce #accordion" ).accordion();
	
	$(".eshopMigrationWooCommerce .imageHandling").on('click',function(){
		$(".eshopMigrationWooCommerce .imageinfo").slideToggle();
		$(".eshopMigrationWooCommerce .productInfo").hide();
		$(".eshopMigrationWooCommerce .updateinfo").hide();				
	});
	
	$(".eshopMigrationWooCommerce .productHandling").on('click',function(){
		$(".eshopMigrationWooCommerce .productInfo").slideToggle();	
		$(".eshopMigrationWooCommerce .imageinfo").hide();
		$(".eshopMigrationWooCommerce .updateinfo").hide();
	});		
	
	$(".eshopMigrationWooCommerce .updateHandling").on('click',function(){
		$(".eshopMigrationWooCommerce .productInfo").hide();	
		$(".eshopMigrationWooCommerce .imageinfo").hide();
		$(".eshopMigrationWooCommerce .updateinfo").slideToggle();
	});


	//drag and drop
	function procsmeproDragDrop(){
		$('.eshopMigrationWooCommerce .draggable').draggable({cancel:false});
		$( ".eshopMigrationWooCommerce .droppable" ).droppable({
			drop: function( event, ui ) {
				$( this ).addClass( "ui-state-highlight" ).val( $( ".ui-draggable-dragging" ).val() );
				$( this ).attr('value',$( ".ui-draggable-dragging" ).attr('key')); //ADDITION VALUE INSTEAD OF KEY
				$( this ).val($( ".ui-draggable-dragging" ).attr('key') ); //ADDITION VALUE INSTEAD OF KEY				
				$( this ).attr('placeholder',$( ".ui-draggable-dragging" ).attr('value')); 				
				$( ".ui-draggable-dragging" ).css('visibility','hidden'); //ADDITION + LINE
				$( this ).css('visibility','hidden'); //ADDITION + LINE
				$( this ).parent().css('background','#90EE90');						
					
				if($("input[name='ID']").hasClass('ui-state-highlight') ){
					$(".hideOnUpdateById").hide();
				}
					
			}		 
		});		
	}
	procsmeproDragDrop();


	function procsmeproautomatch_columns(){		
	}

	$('.eshopMigrationWooCommerce #upload').attr('disabled','disabled');
    $(".eshopMigrationWooCommerce .procsmeFile, .eshopMigrationWooCommerce .usersImportFile, .eshopMigrationWooCommerce .ordersImportFile,  .couponsImportFile ").on('change',function () {
        var smprofileExtension = ['xls', 'xlsx'];
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), smprofileExtension) === -1) {
            alert("Only format allowed: "+smprofileExtension.join(', '));	
			$(".eshopMigrationWooCommerce input[type='submit']").attr('disabled','disabled');
        }else{
			$(".eshopMigrationWooCommerce input[type='submit']").removeAttr('disabled');
			$(".eshopMigrationWooCommerce").find('form').submit();
		}
    });


	$(".eshopMigrationWooCommerce #product_import").on("submit", function (e) {
		e.preventDefault();
				var wpeiData = new FormData();
				$.each($('.procsmeFile')[0].files, function(i, file) {
					wpeiData.append('file', file);
				});	
				wpeiData.append('_wpnonce',$("#_wpnonce").val());
				wpeiData.append('importProducts',$("#importProducts").val() );
				var url= window.location.href;
				$.ajax({
					url: window.location.href,
					data: wpeiData,
					cache: false,
					contentType: false,
					processData: false,
					type: 'POST',
					beforeSend: function() {	
						$("html, body").animate({ scrollTop: 0 }, "slow");
						$('.eshopMigrationWooCommerce').addClass('loading');	
					},					
					success: function(response){
						$(".eshopMigrationWooCommerce .result").slideDown().html($(response).find(".result").html());
						$('.eshopMigrationWooCommerce').removeClass('loading');	
						$("#product_import").fadeOut();	
						
						procsmeproDragDrop();	
						
						procsmeproautomatch_columns();
						
						
						$('.eshopMigrationWooCommerce input#update_only').on("change",function(){
							if ($(this).is(':checked')) {
								$('.eshopMigrationWooCommerce #productupdateBy').show();
								$('.eshopMigrationWooCommerce .hideOnUpdateById').hide();	
							}else{
								$('.eshopMigrationWooCommerce .hideOnUpdateById').show();	
							
							}
						});

						
						$(".eshopMigrationWooCommerce #product_process").on('submit',function(e) {
							e.preventDefault();
							if($("input[name='post_title']").val() !='' || $("input[name='ID']").val() !='' || $("input[name='_sku']").val() !='' ){								
								$(".progressText").fadeIn();
								var total = $(".eshopMigrationWooCommerce input[name='importfinalupload']").val() ;
								$(".eshopMigrationWooCommerce .total").html(total-1);
								var i = 2;	
								$('.eshopMigrationWooCommerce').addClass('loading');
								
								function procsmeproImportProducts() {									
									var start = parseInt($(".eshopMigrationWooCommerce input[name='start']").val() ,10 );
									var total = parseInt( $(".eshopMigrationWooCommerce input[name='importfinalupload']").val(),10 ) ;
									if(start > total  ){
										$('.eshopMigrationWooCommerce .success , .eshopMigrationWooCommerce .error, .eshopMigrationWooCommerce .warning').delay(2000).hide();
										$(".eshopMigrationWooCommerce #product_import").delay(5000).slideDown();
									}else{	
										
										$.ajax({
											url: eshopMigrationWooCommerce.ajax_url,
											data: $(".eshopMigrationWooCommerce #product_process").serialize(),
											type: 'POST',
											beforeSend: function() {
												$("html, body").animate({ scrollTop: 0 }, "slow");												
												$(".eshopMigrationWooCommerce #product_process").hide();
											},						
											success: function(response){
												
												$(".eshopMigrationWooCommerce .importMessage").slideDown().html($(response).find(".importMessage").html());
												$(".eshopMigrationWooCommerce .ajaxResponse").html(response);
												$(".eshopMigrationWooCommerce .thisNum").html($("#AjaxNumber").html() );
												
													$(".eshopMigrationWooCommerce input[name='start']").val(i + 1 );
													i++;
													
											},complete: function(response){
													$('.eshopMigrationWooCommerce').removeClass('loading');
													procsmeproImportProducts();
											}
										});	
									
									}
								}
								
								procsmeproImportProducts();
							}else alert('Title Selection, SKU or Product ID (for update from export file) is Mandatory.');
							
						});							
					}
			});			
	});	





			$(".eshopMigrationWooCommerce .exportToggler").on('click',function(){
				$(".eshopMigrationWooCommerce #exportProductsForm,.eshopMigrationWooCommerce #exportReviewsForm,.eshopMigrationWooCommerce #exportCouponsForm.eshopMigrationWooCommerce #exportOrdersForm").slideToggle();
				$(".eshopMigrationWooCommerce .exportTableWrapper").slideToggle();
				$(".eshopMigrationWooCommerce .downloadToExcel").slideToggle();
				$(".eshopMigrationWooCommerce #selectTaxonomy").slideToggle();
			});



			
			$(".eshopMigrationWooCommerce #exportProductsForm").on('submit',function(e) {
					e.preventDefault();
		

						
				//if checkbox is checked
				$(".eshopMigrationWooCommerce .fieldsToShow").each(function(){
					if($(this).is(':checked')){
					}else localStorage.setItem($(this).attr('name') ,$(this).attr('name') );
				});	
				
				$.ajax({
					url: $(this).attr('action'),
					data:  $(this).serialize(),
					type: 'POST',
					beforeSend: function() {									
						$('.eshopMigrationWooCommerce').addClass('loading');		
					},						
					success: function(response){


						$('.eshopMigrationWooCommerce').removeClass('loading');
						
						$(".eshopMigrationWooCommerce #exportProductsForm").hide();
						$(".eshopMigrationWooCommerce #selectTaxonomy").hide();	
						
						$(".resultExport").slideDown().html($(response).find(".resultExport").html());
							
								//if checkbox is checked
								$(".eshopMigrationWooCommerce .fieldsToShow").each(function(){									
									if (localStorage.getItem($(this).attr('name')) ) {
										$(this).attr('checked', false);
									}//else $(this).attr('checked', false);							
									localStorage.removeItem($(this).attr('name'));	
								});	
									
									var i=0;
									$(".eshopMigrationWooCommerce input[name='total']").val($(".eshopMigrationWooCommerce .totalPosts").html());
									$(".eshopMigrationWooCommerce input[name='start']").val($(".eshopMigrationWooCommerce .startPosts").html());							
									var total = $(".eshopMigrationWooCommerce input[name='total']").val();	
									var start = $(".eshopMigrationWooCommerce input[name='start']").val();

								function procsmeproExportProducts() {
									
									var total = $(".eshopMigrationWooCommerce input[name='total']").val();
									var start = $(".eshopMigrationWooCommerce input[name='start']").val() * i;
									
									if($(".eshopMigrationWooCommerce .totalPosts").html()  <=500){
											$(".eshopMigrationWooCommerce input[name='posts_per_page']").val($(".eshopMigrationWooCommerce .totalPosts").html() );
									}else $(".eshopMigrationWooCommerce input[name='posts_per_page']").val($(".eshopMigrationWooCommerce .startPosts").html());
									
									var dif = total- start;
									
									if( $('.eshopMigrationWooCommerce #toExport >tbody >tr').length >= total ){
																				
										
										$('.eshopMigrationWooCommerce #myProgress').delay(10000).hide('loading');

										$("body").find('#exportProductsForm').find("input[type='number'],input[type='text'], select, textarea").val('');
										$('.eshopMigrationWooCommerce .message').html('Job Done!');
										$('.eshopMigrationWooCommerce .message').addClass('success');
										$('.eshopMigrationWooCommerce .message').removeClass('error');
										
										$(".eshopMigrationWooCommerce #toExport").tableExport();
										
										
									}else{	
									
										var dif = total - start;
										if(total> 500 && dif <=500 ){
											$(".eshopMigrationWooCommerce  input[name='posts_per_page']").val(dif);
										} 									
										
										$.ajax({
											url: eshopMigrationWooCommerce.ajax_url,
											data: $(".eshopMigrationWooCommerce #exportProductsForm").serialize(),
											type: 'POST',
											beforeSend: function() {
												$("html, body").animate({ scrollTop: 0 }, "slow");	
												$('.eshopMigrationWooCommerce').removeClass('loading');
												
											},						
											success: function(response){	

												$(".eshopMigrationWooCommerce .tableExportAjax").append(response);
												i++;
												start = $(".eshopMigrationWooCommerce input[name='start']").val() * i;
												
												$(".eshopMigrationWooCommerce  input[name='offset']").val(start);
												
												var offset = $(".eshopMigrationWooCommerce  input[name='offset']").val();																									
											},complete: function(response){	
																					
												procsmeproExportProducts();	
																								}
										});
									}
								}
								procsmeproExportProducts();								
					}
					});	


										
			});	
	


	
    $('.eshopMigrationWooCommerce #check_all1').on('change',function(){
        var checkboxes = $('.eshopMigrationWooCommerce .tax_checks').find(':checkbox');
        if($(this).prop('checked')) {
          checkboxes.prop('checked', true);
        } else {
          checkboxes.prop('checked', false);
        }
    });
    $('.eshopMigrationWooCommerce #check_all2').on('change',function(){
        var checkboxes = $('.eshopMigrationWooCommerce .fields_checks').find(':checkbox');

			if($(this).prop('checked')) {
			  checkboxes.prop('checked', true);
			} else {
				checkboxes.prop('checked', false);       
			}			
    });			




	$(".eshopMigrationWooCommerce #user_import").on("submit", function (e) {
		e.preventDefault();
				var wpeiData = new FormData();
				$.each($('.usersImportFile')[0].files, function(i, file) {
					wpeiData.append('file', file);
				});	
				wpeiData.append('_wpnonce',$("#_wpnonce").val());
				wpeiData.append('importUsers',$("#importUsers").val() );
				var url= window.location.href;
				$.ajax({
					url: window.location.href,
					data: wpeiData,
					cache: false,
					contentType: false,
					processData: false,
					type: 'POST',
					beforeSend: function() {	
						$("html, body").animate({ scrollTop: 0 }, "slow");
						$('.eshopMigrationWooCommerce').addClass('loading');	
					},					
					success: function(response){
						$(".eshopMigrationWooCommerce .result").slideDown().html($(response).find(".result").html());
						$('.eshopMigrationWooCommerce').removeClass('loading');	
						$("#user_import").fadeOut();
						
						procsmeproDragDrop();
						procsmeproautomatch_columns();
						
						$(".eshopMigrationWooCommerce #user_process").on('submit',function(e) {
							e.preventDefault();
							if($("input[name='email']").val() !=''&& $("input[name='username']").val() !=''  ){								
								$(".progressText").fadeIn();
								var total = $(".eshopMigrationWooCommerce input[name='customersfinalupload']").val() ;
								$(".eshopMigrationWooCommerce .total").html(total-1);
								var i = 2;	
								$('.eshopMigrationWooCommerce').addClass('loading');
								
								function wpeiImportUsers() {									
									var start = parseInt($(".eshopMigrationWooCommerce input[name='start']").val() ,10 );
									var total = parseInt( $(".eshopMigrationWooCommerce input[name='customersfinalupload']").val(),10 ) ;
									if(start > total  ){
										$('.eshopMigrationWooCommerce .success , .eshopMigrationWooCommerce .error, .eshopMigrationWooCommerce .warning').delay(2000).hide();
										$(".eshopMigrationWooCommerce #user_import").delay(5000).slideDown();
									}else{	
										
										$.ajax({
											url: eshopMigrationWooCommerce.ajax_url,
											data: $(".eshopMigrationWooCommerce #user_process").serialize(),
											type: 'POST',
											beforeSend: function() {
												$("html, body").animate({ scrollTop: 0 }, "slow");												
												$(".eshopMigrationWooCommerce #user_process").hide();
											},						
											success: function(response){
												
												$(".eshopMigrationWooCommerce .importMessage").slideDown().html($(response).find(".importMessage").html());
												$(".eshopMigrationWooCommerce .ajaxResponse").html(response);
												$(".eshopMigrationWooCommerce .thisNum").html($("#AjaxNumber").html() );
												
													$(".eshopMigrationWooCommerce input[name='start']").val(i + 1 );
													i++;
													
											},complete: function(response){
													$('.eshopMigrationWooCommerce').removeClass('loading');
													wpeiImportUsers();
											}
										});	
									
									}
								}
								
								wpeiImportUsers();
							}else alert('Email & Username are Mandatory.');
							
						});							
					}
			});			
	});				





	$(".eshopMigrationWooCommerce #orders_import").on("submit", function (e) {
		e.preventDefault();
				var wpeiData = new FormData();
				$.each($('.ordersImportFile')[0].files, function(i, file) {
					wpeiData.append('file', file);
				});	
				wpeiData.append('_wpnonce',$("#_wpnonce").val());
				wpeiData.append('importOrders',$("#importOrders").val() );
				var url= window.location.href;
				$.ajax({
					url: window.location.href,
					data: wpeiData,
					cache: false,
					contentType: false,
					processData: false,
					type: 'POST',
					beforeSend: function() {	
						$("html, body").animate({ scrollTop: 0 }, "slow");
						$('.eshopMigrationWooCommerce').addClass('loading');	
					},					
					success: function(response){
						$(".eshopMigrationWooCommerce .result").slideDown().html($(response).find(".result").html());
						$('.eshopMigrationWooCommerce').removeClass('loading');	
						$("#orders_import").fadeOut();	
						
						procsmeproDragDrop();
						procsmeproautomatch_columns();
						
						$(".eshopMigrationWooCommerce #orders_process").on('submit',function(e) {
							e.preventDefault();
							if($("input[name='product_ids']").val() !='' && $("input[name='quantities']").val() !='' && $("#productBy").val() != '' ){
								$(".progressText").fadeIn();
								var total = $(".eshopMigrationWooCommerce input[name='ordersfinalupload']").val() ;
								$(".eshopMigrationWooCommerce .total").html(total-1);
								var i = 2;	
								$('.eshopMigrationWooCommerce').addClass('loading');
								
								function ordersImport() {									
									var start = parseInt($(".eshopMigrationWooCommerce input[name='start']").val() ,10 );
									var total = parseInt( $(".eshopMigrationWooCommerce input[name='ordersfinalupload']").val(),10 ) ;
									if(start > total  ){
										$('.eshopMigrationWooCommerce .success , .eshopMigrationWooCommerce .error, .eshopMigrationWooCommerce .warning').delay(2000).hide();
										$(".eshopMigrationWooCommerce #orders_import").delay(5000).slideDown();
									}else{	
										
										$.ajax({
											url: eshopMigrationWooCommerce.ajax_url,
											data: $(".eshopMigrationWooCommerce #orders_process ").serialize(),
											type: 'POST',
											beforeSend: function() {
												$("html, body").animate({ scrollTop: 0 }, "slow");												
												$(".eshopMigrationWooCommerce #orders_process").hide();
											},						
											success: function(response){
												$(".eshopMigrationWooCommerce .importMessage").slideDown().html($(response).find(".importMessage").html());
												$(".eshopMigrationWooCommerce .ajaxResponse").html(response);
												$(".eshopMigrationWooCommerce .thisNum").html($("#AjaxNumber").html() );
												
													$(".eshopMigrationWooCommerce input[name='start']").val(i + 1 );
													i++;
													
											},complete: function(response){
													$('.eshopMigrationWooCommerce').removeClass('loading');
													ordersImport();
											}
										});	
									
									}
								}
								
								ordersImport();
							}else alert('Product IDs ,Quantities and products identifier are Mandatory.');
							
						});							
					}
			});			
	});	


	$(".eshopMigrationWooCommerce #coupons_import").on("submit", function (e) {
		e.preventDefault();
				var wpeiData = new FormData();
				$.each($('.couponsImportFile')[0].files, function(i, file) {
					wpeiData.append('file', file);
				});	
				wpeiData.append('_wpnonce',$("#_wpnonce").val());
				wpeiData.append('importCoupons',$("#importCoupons").val() );
				var url= window.location.href;
				$.ajax({
					url: window.location.href,
					data: wpeiData,
					cache: false,
					contentType: false,
					processData: false,
					type: 'POST',
					beforeSend: function() {	
						$("html, body").animate({ scrollTop: 0 }, "slow");
						$('.eshopMigrationWooCommerce').addClass('loading');	
					},					
					success: function(response){
						$(".eshopMigrationWooCommerce .result").slideDown().html($(response).find(".result").html());
						$('.eshopMigrationWooCommerce').removeClass('loading');	
						$("#coupons_import").fadeOut();	
						
						procsmeproDragDrop();
						procsmeproautomatch_columns();
						
						$(".eshopMigrationWooCommerce #coupons_process").on('submit',function(e) {
							e.preventDefault();
							if($("input[name='coupon_code']").val() !=''&& $("input[name='coupon_amount']").val() !=''  ){								
								$(".progressText").fadeIn();
								var total = $(".eshopMigrationWooCommerce input[name='couponsfinalupload']").val() ;
								$(".eshopMigrationWooCommerce .total").html(total-1);
								var i = 2;	
								$('.eshopMigrationWooCommerce').addClass('loading');
								
								function couponsImport() {									
									var start = parseInt($(".eshopMigrationWooCommerce input[name='start']").val() ,10 );
									var total = parseInt( $(".eshopMigrationWooCommerce input[name='couponsfinalupload']").val(),10 ) ;
									if(start > total  ){
										$('.eshopMigrationWooCommerce .success , .eshopMigrationWooCommerce .error, .eshopMigrationWooCommerce .warning').delay(2000).hide();
										$(".eshopMigrationWooCommerce #coupons_import").delay(5000).slideDown();
									}else{	
										
										$.ajax({
											url: eshopMigrationWooCommerce.ajax_url,
											data: $(".eshopMigrationWooCommerce #coupons_process ").serialize(),
											type: 'POST',
											beforeSend: function() {
												$("html, body").animate({ scrollTop: 0 }, "slow");												
												$(".eshopMigrationWooCommerce #coupons_process").hide();
											},						
											success: function(response){
												$(".eshopMigrationWooCommerce .importMessage").slideDown().html($(response).find(".importMessage").html());
												$(".eshopMigrationWooCommerce .ajaxResponse").html(response);
												$(".eshopMigrationWooCommerce .thisNum").html($("#AjaxNumber").html() );
												
													$(".eshopMigrationWooCommerce input[name='start']").val(i + 1 );
													i++;
													
											},complete: function(response){
													$('.eshopMigrationWooCommerce').removeClass('loading');
													couponsImport();
											}
										});	
									
									}
								}
								
								couponsImport();
							}else alert('Coupon Name and Amount are Mandatory.');
							
						});							
					}
			});			
	});	


	/*	EXPORT PROCESSES	*/

			$(".eshopMigrationWooCommerce #exportUsersForm").on('submit',function(e) {
					e.preventDefault();
		
					
				//if checkbox is checked
				$(".eshopMigrationWooCommerce .fieldsToShow").each(function(){
					if($(this).is(':checked')){
					}else localStorage.setItem($(this).attr('name') ,$(this).attr('name') );
				});	
				
				$.ajax({
					url: $(this).attr('action'),
					data:  $(this).serialize(),
					type: 'POST',
					beforeSend: function() {									
						$('.eshopMigrationWooCommerce').addClass('loading');		
					},						
					success: function(response){
						
						$('.eshopMigrationWooCommerce').removeClass('loading');
						
						$(".eshopMigrationWooCommerce #exportUsersForm").hide();
						
						$(".resultExport").slideDown().html($(response).find(".resultExport").html());
							
								//if checkbox is checked
								$(".eshopMigrationWooCommerce .fieldsToShow").each(function(){									
									if (localStorage.getItem($(this).attr('name')) ) {
										$(this).attr('checked', false);
									}//else $(this).attr('checked', false);							
									localStorage.removeItem($(this).attr('name'));	
								});	
									
									var i=0;
									$(".eshopMigrationWooCommerce input[name='total']").val($(".eshopMigrationWooCommerce .totalPosts").html());
									$(".eshopMigrationWooCommerce input[name='start']").val($(".eshopMigrationWooCommerce .startPosts").html());							
									var total = $(".eshopMigrationWooCommerce input[name='total']").val();	
									var start = $(".eshopMigrationWooCommerce input[name='start']").val();
									//progressBar(start,total) ;

								function wpeiDataExportUsers() {
									var total = $(".eshopMigrationWooCommerce input[name='total']").val();
									var start = $(".eshopMigrationWooCommerce input[name='start']").val() * i;
									
									if($(".eshopMigrationWooCommerce .totalPosts").html()  <=500){
											$(".eshopMigrationWooCommerce input[name='posts_per_page']").val($(".eshopMigrationWooCommerce .totalPosts").html() );
									}else $(".eshopMigrationWooCommerce input[name='posts_per_page']").val($(".eshopMigrationWooCommerce .startPosts").html());
									
									var dif = total- start;
									
									if( $('#toExport >tbody >tr').length >= total ){
																				
										
										$('.eshopMigrationWooCommerce #myProgress').delay(10000).hide('loading');

					
										$("body").find('#exportUsersForm').find("input[type='number'],input[type='text'], select, textarea").val('');
										
										if( $('#toExport >tbody >tr').length >= 1 ){
											$('.eshopMigrationWooCommerce .message').html('Job Done!');
										}
										
										$('.eshopMigrationWooCommerce .message').addClass('success');
										$('.eshopMigrationWooCommerce .message').removeClass('error');
										
										$(".eshopMigrationWooCommerce #toExport").tableExport();		
										
									}else{	
									
										var dif = total - start;
										if(total> 500 && dif <=500 ){
											$(".eshopMigrationWooCommerce  input[name='posts_per_page']").val(dif);
										} 									
										
										$.ajax({
											url: eshopMigrationWooCommerce.ajax_url,
											data: $(".eshopMigrationWooCommerce #exportUsersForm").serialize(),
											type: 'POST',
											beforeSend: function() {
												$("html, body").animate({ scrollTop: 0 }, "slow");	
												$('.eshopMigrationWooCommerce').removeClass('loading');
											},						
											success: function(response){	

												$(".eshopMigrationWooCommerce .tableExportAjax").append(response);
												i++;
												start = $(".eshopMigrationWooCommerce input[name='start']").val() * i;
												
												$(".eshopMigrationWooCommerce  input[name='offset']").val(start);												
												var offset = $(".eshopMigrationWooCommerce  input[name='offset']").val();													
												
											},complete: function(response){			
													wpeiDataExportUsers();	
											}
										});
									}
								}
								wpeiDataExportUsers();								
					}
					});	
			});	



			$(".eshopMigrationWooCommerce #exportCouponsForm").on('submit',function(e) {
					e.preventDefault();
		
					
				//if checkbox is checked
				$(".eshopMigrationWooCommerce .fieldsToShow").each(function(){
					if($(this).is(':checked')){
					}else localStorage.setItem($(this).attr('name') ,$(this).attr('name') );
				});	
				
				$.ajax({
					url: $(this).attr('action'),
					data:  $(this).serialize(),
					type: 'POST',
					beforeSend: function() {									
						$('.eshopMigrationWooCommerce').addClass('loading');		
					},						
					success: function(response){
						
						$('.eshopMigrationWooCommerce').removeClass('loading');
						
						$(".eshopMigrationWooCommerce #exportCouponsForm").hide();
						
						$(".resultExport").slideDown().html($(response).find(".resultExport").html());
							
								//if checkbox is checked
								$(".eshopMigrationWooCommerce .fieldsToShow").each(function(){									
									if (localStorage.getItem($(this).attr('name')) ) {
										$(this).attr('checked', false);
									}//else $(this).attr('checked', false);							
									localStorage.removeItem($(this).attr('name'));	
								});	
									
									var i=0;
									$(".eshopMigrationWooCommerce input[name='total']").val($(".eshopMigrationWooCommerce .totalPosts").html());
									$(".eshopMigrationWooCommerce input[name='start']").val($(".eshopMigrationWooCommerce .startPosts").html());							
									var total = $(".eshopMigrationWooCommerce input[name='total']").val();	
									var start = $(".eshopMigrationWooCommerce input[name='start']").val();
									//progressBar(start,total) ;

								function wpeiDataExportCoupons() {
									var total = $(".eshopMigrationWooCommerce input[name='total']").val();
									var start = $(".eshopMigrationWooCommerce input[name='start']").val() * i;
									
									if($(".eshopMigrationWooCommerce .totalPosts").html()  <=500){
											$(".eshopMigrationWooCommerce input[name='posts_per_page']").val($(".eshopMigrationWooCommerce .totalPosts").html() );
									}else $(".eshopMigrationWooCommerce input[name='posts_per_page']").val($(".eshopMigrationWooCommerce .startPosts").html());
									
									var dif = total- start;
									
									if( $('#toExport >tbody >tr').length >= total ){
																				
										
										$('.eshopMigrationWooCommerce #myProgress').delay(10000).hide('loading');

					
										$("body").find('#exportCouponsForm').find("input[type='number'],input[type='text'], select, textarea").val('');
										if( $('#toExport >tbody >tr').length >= 1 ){
											$('.eshopMigrationWooCommerce .message').html('Job Done!');
										}
										$('.eshopMigrationWooCommerce .message').addClass('success');
										$('.eshopMigrationWooCommerce .message').removeClass('error');
										
										$(".eshopMigrationWooCommerce #toExport").tableExport();		
										
									}else{	
									
										var dif = total - start;
										if(total> 500 && dif <=500 ){
											$(".eshopMigrationWooCommerce  input[name='posts_per_page']").val(dif);
										} 									
										
										$.ajax({
											url: eshopMigrationWooCommerce.ajax_url,
											data: $(".eshopMigrationWooCommerce #exportCouponsForm").serialize(),
											type: 'POST',
											beforeSend: function() {
												$("html, body").animate({ scrollTop: 0 }, "slow");	
												$('.eshopMigrationWooCommerce').removeClass('loading');
											},						
											success: function(response){	

												$(".eshopMigrationWooCommerce .tableExportAjax").append(response);
												i++;
												start = $(".eshopMigrationWooCommerce input[name='start']").val() * i;
												
												$(".eshopMigrationWooCommerce  input[name='offset']").val(start);												
												var offset = $(".eshopMigrationWooCommerce  input[name='offset']").val();													
												
											},complete: function(response){			
													wpeiDataExportCoupons();	
											}
										});
									}
								}
								wpeiDataExportCoupons();								
					}
					});	
			});	




			$(".eshopMigrationWooCommerce #exportOrdersForm").on('submit',function(e) {
					e.preventDefault();
		
					
				//if checkbox is checked
				$(".eshopMigrationWooCommerce .fieldsToShow").each(function(){
					if($(this).is(':checked')){
					}else localStorage.setItem($(this).attr('name') ,$(this).attr('name') );
				});	
				
				$.ajax({
					url: $(this).attr('action'),
					data:  $(this).serialize(),
					type: 'POST',
					beforeSend: function() {									
						$('.eshopMigrationWooCommerce').addClass('loading');		
					},						
					success: function(response){
						
						$('.eshopMigrationWooCommerce').removeClass('loading');
						
						$(".eshopMigrationWooCommerce #exportOrdersForm").hide();
						
						$(".resultExport").slideDown().html($(response).find(".resultExport").html());
							
								//if checkbox is checked
								$(".eshopMigrationWooCommerce .fieldsToShow").each(function(){									
									if (localStorage.getItem($(this).attr('name')) ) {
										$(this).attr('checked', false);
									}//else $(this).attr('checked', false);							
									localStorage.removeItem($(this).attr('name'));	
								});	
									
									var i=0;
									$(".eshopMigrationWooCommerce input[name='total']").val($(".eshopMigrationWooCommerce .totalPosts").html());
									$(".eshopMigrationWooCommerce input[name='start']").val($(".eshopMigrationWooCommerce .startPosts").html());							
									var total = $(".eshopMigrationWooCommerce input[name='total']").val();	
									var start = $(".eshopMigrationWooCommerce input[name='start']").val();
									//progressBar(start,total) ;

								function wpeiDataExportOrders() {
									var total = $(".eshopMigrationWooCommerce input[name='total']").val();
									var start = $(".eshopMigrationWooCommerce input[name='start']").val() * i;
									
									if($(".eshopMigrationWooCommerce .totalPosts").html()  <=500){
											$(".eshopMigrationWooCommerce input[name='posts_per_page']").val($(".eshopMigrationWooCommerce .totalPosts").html() );
									}else $(".eshopMigrationWooCommerce input[name='posts_per_page']").val($(".eshopMigrationWooCommerce .startPosts").html());
									
									var dif = total- start;
									
									if( $('#toExport >tbody >tr').length >= total ){
																				
										
										$('.eshopMigrationWooCommerce #myProgress').delay(10000).hide('loading');

					
										$("body").find('#exportOrdersForm').find("input[type='number'],input[type='text'], select, textarea").val('');
										if( $('#toExport >tbody >tr').length >= 1 ){
											$('.eshopMigrationWooCommerce .message').html('Job Done!');
										}
										$('.eshopMigrationWooCommerce .message').addClass('success');
										$('.eshopMigrationWooCommerce .message').removeClass('error');
										
										$(".eshopMigrationWooCommerce #toExport").tableExport();		
										
									}else{	
									
										var dif = total - start;
										if(total> 500 && dif <=500 ){
											$(".eshopMigrationWooCommerce  input[name='posts_per_page']").val(dif);
										} 									
										
										$.ajax({
											url: eshopMigrationWooCommerce.ajax_url,
											data: $(".eshopMigrationWooCommerce #exportOrdersForm").serialize(),
											type: 'POST',
											beforeSend: function() {
												$("html, body").animate({ scrollTop: 0 }, "slow");	
												$('.eshopMigrationWooCommerce').removeClass('loading');
											},						
											success: function(response){	

												$(".eshopMigrationWooCommerce .tableExportAjax").append(response);
												i++;
												start = $(".eshopMigrationWooCommerce input[name='start']").val() * i;
												
												$(".eshopMigrationWooCommerce  input[name='offset']").val(start);												
												var offset = $(".eshopMigrationWooCommerce  input[name='offset']").val();													
												
											},complete: function(response){			
													wpeiDataExportOrders();	
											}
										});
									}
								}
								wpeiDataExportOrders();								
					}
					});	
			});

	
		
		
})( jQuery )	