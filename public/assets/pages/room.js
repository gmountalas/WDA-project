(function ($) {
    
    $(document).ready( e => {
        var searchURI = document.baseURI;
        var result = $('#booking-result').html();
        history.replaceState({result}, '', searchURI);
    });

    $(document).on('submit', 'form.favoriteForm', function(e) {
        // Stop default form behavior
        e.preventDefault();

        // Get form data
        const formData = $(this).serialize();

        //Ajax request
        $.ajax(
            'http://hotel.collegelink.localhost/public/ajax/room_favorite.php',
            {
                type: "POST",
                dataType: "json",
                data: formData
            }).done(function(result) {
                if (result.status) {
                    $('input[name=is_favorite]').val(result.is_favorite ? 1 : 0);
                } else {
                    $('.heart #fav-heart').toggleClass('selected', !result.is_favorite);
                }
            }).fail(function(jqXHR, textStatus, errorThrown){
                alert("An ajax error occurred: " + textStatus + " : " + errorThrown);
            });   
    });

    $(document).on('submit', 'form.reviewForm', function(e) {
        // Stop default form behavior
        e.preventDefault();
        
        // Form Validation
        const $starRating = $("form input[name=rate]");
        const $review_Error = $('.rating_error');
        
        let valid = false;

        for (let i = 0; i < $starRating.length; i++) {
            if ($starRating[i].checked) {
                valid = true;
            }
        }
        if (!valid) {
            $review_Error.text("Please select a rating");
            // $review_Error.width("17%");
            $('.ratingReview').addClass("c-error");
        } else {
            $review_Error.text("");
            $('.ratingReview').removeClass("c-error");
        }
        
        // Get form data
        const formData = $(this).serialize();

        // Ajax start
        $(document).ajaxStart(function () {  
            // Set form and textarea background color when disabled to white with opacity
            $("form.reviewForm, textarea").css("background-color", "rgba(255,255,255,0.25)"); 

            // Disable form input and textarea
            $("form.reviewForm input, form.reviewForm textarea").prop("disabled", true);

            // Show loading gif
            $("#loadingImg").show();  
        });

        // // Ajax stop
        $(document).ajaxStop(function () { 
            // Set form and textarea background color when disabled to white
            $("form.reviewForm, textarea").css("background-color", "#ffffff");

            // Enable form input and textarea
            $("form.reviewForm input, form.reviewForm textarea").prop("disabled", false); 
            
            // Hide loading gif
            $("#loadingImg").hide();  

            // Change the numbering of every review after the result has been prepended from the ajax request
            $("div.box").each(function(index) {
                let textContent = $(this).find("p").text();
                let stringArray = textContent.split(".");
                stringArray[0] = `${index + 1}`;
                textContent = stringArray.join(".");
                $(this).find("p").text(textContent);
            });

        });

        //Ajax request
        $.ajax(
            'http://hotel.collegelink.localhost/public/ajax/room_review.php',
            {
                type: "POST",
                dataType: "html",
                data: formData
            }).done(function(result) {
                $('#room-reviews-container').prepend(result);
                $('form.reviewForm').trigger('reset');
            }).fail(function(jqXHR, textStatus, errorThrown){
                alert("An ajax error occurred: " + textStatus + " : " + errorThrown);
            });   
    });

    // Check room availability with check-in and check-out calendars
    $(document).on('submit', 'form.checkForm', function(e) {
        // Stop default form behavior
        e.preventDefault();

        // Get form data
        const formData = $(this).serialize();

        // Ajax request
        $.ajax(
            'http://hotel.collegelink.localhost/public/ajax/room_availability.php',
            {
                type: "POST",
                dataType: "html",
                data: formData,
                beforeSend: function() {
                    $('#booking-result').html('');
                    $('#booking-result').append('<img id="loadingImg" src="./assets/images/ajax-loader.gif" width="auto" height="100%" style="display:block; margin: 0 2rem 0 auto"/>');
                }
            }).done(function(result) {

                // Clear results container
                $('#booking-result').html('');

                // Append results container
                $('#booking-result').append(result);

                // Push url state
                history.pushState({result}, '', 'http://hotel.collegelink.localhost/public/room.php?' + formData);
            }) 
    });

    // Set result content from history when pressing Back or Forward buttons on browser
    $(window).on('popstate', function () {
        if (history.state != null) {
            var state = $(history.state.result);
            // Clear results container
            $('#booking-result').html('');

            // Append saved results from history
            $('#booking-result').append(state);
            
            // Retain check-in and check-out dates from url query
            $('#checkInDate').val(window.location.search.split('&')[1].split('=')[1]);
            $('#checkOutDate').val(window.location.search.split('&')[2].split('=')[1]);
        }   
    });
})(jQuery);