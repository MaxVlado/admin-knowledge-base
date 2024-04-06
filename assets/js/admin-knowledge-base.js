(function($) {
    $(document).ready(function() {
        // Scripts for a page with a list of knowledge base entries
        $('.akb-search-form').submit(function(e) {
            e.preventDefault();
            var searchQuery = $('.akb-search-input').val();
            var url = new URL(window.location.href);
            url.searchParams.set('s', searchQuery);
            window.location.href = url.href;
        });

    });
})(jQuery);