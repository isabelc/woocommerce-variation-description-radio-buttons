jQuery(document).ready(function(a) {
    a(".woocommerce-tabs .panel").hide(), a(".woocommerce-tabs ul.tabs li a").click(function() {
        var e = a(this),
            s = e.closest(".woocommerce-tabs");
        return a("ul.tabs li", s).removeClass("active"), a("div.panel", s).hide(), a("div" + e.attr("href")).show(), e.parent().addClass("active"), !1
    }), a(".woocommerce-tabs").each(function() {
        var e = window.location.hash;
        e.toLowerCase().indexOf("comment-") >= 0 ? a("ul.tabs li.reviews_tab a", a(this)).click() : a("ul.tabs li:first a", a(this)).click()
    }), a("#rating").hide().before('<p class="stars"><span><a class="star-1" href="#">1</a><a class="star-2" href="#">2</a><a class="star-3" href="#">3</a><a class="star-4" href="#">4</a><a class="star-5" href="#">5</a></span></p>'), a("p.stars a").click(function() {
        var e = a(this);
        return a("#rating").val(e.text()), a("p.stars a").removeClass("active"), e.addClass("active"), !1
    }), a("#review_form #submit").live("click", function() {
        var e = a("#rating").val();
        return a("#rating").size() > 0 && !e && "yes" == woocommerce_params.review_rating_required ? (alert(woocommerce_params.required_rating_text), !1) : void 0
    })
});
