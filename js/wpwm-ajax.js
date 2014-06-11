
var $j = jQuery;

$j(document).ajaxStop($j.unblockUI); 

$j(function() {

    if (!$j.isFunction($j.fn.on) ) {
        alert('ExNet Movies Plugin requires at least jQuery 1.7 for .on() function support (.live() replacement)');
    }

    $j('#exnet-tabs').tabify();
    $j('.exnet-tabs').tabify();

     $j('#exnet-filter').on('submit', function() { 
        $j('.exnet-paged').val(1);

        $j.blockUI({ message: $j('#domMessage') }); 
        $j(this).ajaxSubmit(
            {
                target: '.exnet-items-ajax', 
                success:       addRating
            }
        );

        return false; 
    });

     $j('.exnet-filter-toggle').click(function() {
     	$j('#exnet-filtering-options').toggle('slow');
     });

     function addRating() {
        $j('.rateit').rateit();
     }

     if(jQuery().live) {
        
        var bind = $j('.exnet-load-more').live('click', function() {
            var id = parseInt($j(this).attr("id"));
            $j('.exnet-paged').val(id);

            $j.blockUI({ message: $j('#domMessage') }); 


            $j('#exnet-filter').ajaxSubmit(
                {
                    target: '.exnet-items-ajax', 
                    success:       addRating
                }
            );

            return false; 
        });

     }else{
        
        $j('.exnet-load-more').on('click', function() {
            var id = parseInt($j(this).attr("id"));
            $j('.exnet-paged').val(id);

            $j.blockUI({ message: $j('#domMessage') }); 
            $j('#exnet-filter').ajaxSubmit(
                {
                    target: '.exnet-items-ajax', 
                    success:       addRating
                }
            );

            return false; 
        });

     }

     $j('.rateit').bind('rated reset', function (e) {
         var ri = $j(this);
 
         var value = ri.rateit('value');
         var movieID = ri.data('movieid');
 
         //maybe we want to disable voting?
         ri.rateit('readonly', true);

         $j.ajax({
             url: '/wp-admin/admin-ajax.php',
             data: { id: movieID, value: value, action: 'exnet_rate' }, //our data
             type: 'POST',
             success: function (data) {
                 $j('.rateit-result-' + movieID).html('<li>' + data + '</li>');
             },
             error: function (jxhr, msg, err) {
                 $j('.rateit-result-' + movieID).html('<li style="color:red">' + msg + '</li>');
             }
         });
 
         
     });

     $j('.exnet-rate-link').on('click', function() {
            var element = $j(this);
            var movie = $j(this).attr('id');
            
            if(movie.indexOf("linkok-") > 0) {
                var movie_id = movie.substring(movie.indexOf("-")+1);
                var link_status = 'ok';
            }else if(movie.indexOf("linkbroken-") > 0) {
                var movie_id = movie.substring(movie.indexOf("-")+1);
                var link_status = 'broken';
            }

            if(typeof movie_id != 'undefined') {

                $j.ajax({
                         url: '/wp-admin/admin-ajax.php',
                         data: { id: movie_id, value: link_status, action: 'exnet_link_rate' }, //our data
                         type: 'POST',
                         success: function (data) {
                             //$j(element).after("<br/>" + data.replace("0", "") + "<br/>");
                             $j(element).text(data.replace("0", ""));
                         },
                         error: function (jxhr, msg, err) {
                             alert(msg);
                         }
                     });

            }

     });
        
});