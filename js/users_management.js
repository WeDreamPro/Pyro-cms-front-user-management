(function($,undefined){
    
    $(document).on('click', '.delete-confirm', function(){
        var $this = $(this);
        $('#DeleteUser').find('#confirmDeleteUser').data('url', $this.attr('href'));
        $('#DeleteUser').modal('show');
        return false;
    });
    
    $(document).on('click', '#confirmDeleteUser', function(){
        var $this = $(this);
        $this.button('loading');
        window.location.href = $this.data('url');
    });
    
    $(function(){
        $('input').addClass('form-control');
        $('.dataTable').dataTable();
    });
    
})($);

