var Xtreme_startup_calls = Xtreme_startup_calls || [];
Xtreme_startup_calls.push(function() {
    //
    list_length();
    //
    $('#navi_toggler').click(function() {
        $('#navigation ul').toggleClass('active');
    });
});
