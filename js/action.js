jQuery(document).ready(function($) {
  var top1 = $("#hero").offset().top - 100;
  var top2 = $("#about").offset().top - 100;
  var top3 = $("#services").offset().top - 100;
  var top4 = $("#team").offset().top - 100;
  var top5 = $("#contact").offset().top - 100;
  $(document).scroll(function() {
    var scrollPos = $(document).scrollTop();
    if (scrollPos >= top1 && scrollPos < top2) {
      $(".1").css("color", "#90581a");
      $(".2").css("color", "#fff");
      $(".1").css("font-weight", "bold");
      $(".2").css("font-weight", "bold");
      $(".3").css("font-weight", "bold");
      $(".4").css("font-weight", "bold");
      $(".5").css("font-weight", "bold");
    } else if (scrollPos >= top2 && scrollPos < top3) {
      $(".1").css("color", "#fff");
      $(".3").css("color", "#fff");
      $(".4").css("color", "#fff");
      $(".5").css("color", "#fff");
      $(".2").css("color", "#90581a");
      $(".1").css("font-weight", "bold");
      $(".2").css("font-weight", "bold");
      $(".3").css("font-weight", "bold");
      $(".4").css("font-weight", "bold");
      $(".5").css("font-weight", "bold");
    } else if (scrollPos >= top3 && scrollPos < top4) {
      $(".1").css("color", "#fff");
      $(".2").css("color", "#fff");
      $(".4").css("color", "#fff");
      $(".5").css("color", "#fff");
      $(".3").css("color", "#90581a");
      $(".1").css("font-weight", "bold");
      $(".2").css("font-weight", "bold");
      $(".3").css("font-weight", "bold");
      $(".4").css("font-weight", "bold");
      $(".5").css("font-weight", "bold");
    } else if (scrollPos >= top4 && scrollPos < top5) {
      $(".1").css("color", "#fff");
      $(".3").css("color", "#fff");
      $(".2").css("color", "#fff");
      $(".5").css("color", "#fff");
      $(".4").css("color", "#90581a");
      $(".1").css("font-weight", "bold");
      $(".2").css("font-weight", "bold");
      $(".3").css("font-weight", "bold");
      $(".4").css("font-weight", "bold");
      $(".5").css("font-weight", "bold");
    } else if (
      scrollPos >= top5 || $("#contact").mouseenter()
    ) {
      $(".1").css("color", "#fff");
      $(".3").css("color", "#fff");
      $(".4").css("color", "#fff");
      $(".2").css("color", "#fff");
      $(".5").css("color", "#90581a");
      $(".1").css("font-weight", "bold");
      $(".2").css("font-weight", "bold");
      $(".3").css("font-weight", "bold");
      $(".4").css("font-weight", "bold");
      $(".5").css("font-weight", "bold");
    }
  });
});
