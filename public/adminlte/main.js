$(function() {
    $('.delete').click(function() {
        let res = confirm('Confirm delete');
        if (!res) return false;
    });
});