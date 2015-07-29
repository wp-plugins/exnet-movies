
var $j = jQuery;

$j(document).ajaxStop($j.unblockUI); 

$j(function() {

    if (!$j.isFunction($j.fn.on) ) {
        alert('Watch Movies Plugin requires at least jQuery 1.7 for .on() function support (.live() replacement)');
    }

    $j('#themo-tabs').tabify();
    $j('.themo-tabs').tabify();

     $j('#themo-filter').on('submit', function() { 
        $j('.themo-paged').val(1);

        $j.blockUI({ message: $j('#domMessage') }); 
        $j(this).ajaxSubmit(
            {
                target: '.themo-items-ajax', 
                success:       addRating
            }
        );

        return false; 
    });

     $j('.themo-filter-toggle').click(function() {
     	$j('#themo-filtering-options').toggle('slow');
     });

     function addRating() {
        $j('.rateit').rateit();
     }

     if(jQuery().live) {
        
        var bind = $j('.themo-load-more').live('click', function() {
            var id = parseInt($j(this).attr("id"));
            $j('.themo-paged').val(id);

            $j.blockUI({ message: $j('#domMessage') }); 


            $j('#themo-filter').ajaxSubmit(
                {
                    target: '.themo-items-ajax', 
                    success:       addRating
                }
            );

            return false; 
        });

     }else{
        
        $j('.themo-load-more').on('click', function() {
            var id = parseInt($j(this).attr("id"));
            $j('.themo-paged').val(id);

            $j.blockUI({ message: $j('#domMessage') }); 
            $j('#themo-filter').ajaxSubmit(
                {
                    target: '.themo-items-ajax', 
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
             data: { id: movieID, value: value, action: 'themo_rate' }, //our data
             type: 'POST',
             success: function (data) {
                 $j('.rateit-result-' + movieID).html('<li>' + data + '</li>');
             },
             error: function (jxhr, msg, err) {
                 $j('.rateit-result-' + movieID).html('<li style="color:red">' + msg + '</li>');
             }
         });
 
         
     });

     $j('.themo-rate-link').on('click', function() {
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
                         data: { id: movie_id, value: link_status, action: 'themo_link_rate' }, //our data
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