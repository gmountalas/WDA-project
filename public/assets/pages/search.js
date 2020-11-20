(function ($) {

    // Place first page results (before the first Ajax request) in history.
    // That way when the back button is pressed all the way to the first list page
    // the search results will be added by the $(window).on('popstate', function()) 
    // as if the first page results were an ajax request
    $(document).ready( e => {
        var searchURI = document.baseURI;
        var result = $('#search-results-container').html();
        
        history.replaceState({result}, '', searchURI);
    });

    
    $(document).on('submit', 'form.searchForm', function(e) {
        // Stop default form behavior
        e.preventDefault();

        // Form validation
        const $check_In_Date = $('#checkInDate');
        const $check_Out_Date = $('#checkOutDate');
        const $check_In_Date_Error = $('.check_in_date_error');
        const $check_Out_Date_Error = $('.check_out_date_error');
        
        let checkInDate_isValid = false;
        let checkOutDate_isValid = false;

        if ($check_In_Date.val() === "") {
          $check_In_Date_Error.text("Please enter a Check-in Date");
          $('.check-in-date').addClass("c-error");
        } else {
          $check_In_Date_Error.text("");
          $('.check-in-date').removeClass("c-error");
          checkInDate_isValid = true;
        }
        if ($check_Out_Date.val() === "") {
          $check_Out_Date_Error.text("Please enter a Check-out Date");
          $('.check-out-date').addClass("c-error");
        } else if ($check_Out_Date.val() <= $check_In_Date.val()) {
          $check_Out_Date_Error.text("Check-out Date can't be earlier than Check-in Date");
          $('.check-out-date').addClass("c-error");
        } else {
          $check_Out_Date_Error.text("");
          $('.check-out-date').removeClass("c-error");
          checkOutDate_isValid = true;
        }
        
        if (checkInDate_isValid && checkOutDate_isValid) {
         
            // Get form data
            const formData = $(this).serialize();

            //Ajax request
            $.ajax(
                'http://hotel.collegelink.localhost/public/ajax/search_results.php',
                {
                    type: "GET",
                    dataType: "html",
                    data: formData,
                    beforeSend: function() {
                        $('#search-results-container').html('');
                        $('#search-results-container').append('<img id="loadingImg" src="./assets/images/loading.gif" width="100px" height="auto" style="display:block; margin:0 auto"/>');
                    }
                }).done(function(result) {

                    // Clear results container
                    $('#search-results-container').html('');

                    // Append results container
                    $('#search-results-container').append(result);

                    // Push url state
                    history.pushState({result}, '', 'http://hotel.collegelink.localhost/public/list.php?' + formData);
            });   
        }
    });
    // Set result content from history when pressing Back or Forward buttons on browser
    $(window).on('popstate', function () {
        if (history.state != null) {
            var state = $(history.state.result);
            // Clear results container
            $('#search-results-container').html('');

             // Append saved results from history
            $('#search-results-container').append(state);

            // Retain form data from url query
            $('#checkInDate').val(window.location.search.split('&')[5].split('=')[1]);
            $('#checkOutDate').val(window.location.search.split('&')[6].split('=')[1]);
            $('#formCity').val(window.location.search.split('&')[2].split('=')[1]);
            $('#formRoom-Type').val(window.location.search.split('&')[1].split('=')[1]);
            $('#formGuest-number').val(window.location.search.split('&')[0].split('=')[1]);
        }   
    });
    

})(jQuery);