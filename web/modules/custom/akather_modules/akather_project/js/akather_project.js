(function ($, Drupal, window) {

  "use strict";
  const second = 1000,
    minute = second * 60,
    hour = minute * 60,
    day = hour * 24;

  Drupal.behaviors.akather_project = {
    attach: function (context, settings) {

    }
  };

  $(document).ready(function () {
    const countdown = document.getElementById("countdown");
    if (countdown) {
      const countDown = new Date(drupalSettings.endDateTime).getTime(),
        x = setInterval(function () {

          const now = new Date().getTime(),
            distance = countDown - now;
            document.getElementById("days").innerText = Math.floor(distance / (day)),
            document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
            document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
            document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);

          //do something later when date is reached
          if (distance < 0) {
            // document.getElementById("headline").innerText = "It's my birthday!";
            document.getElementById("countdown").style.display = "none";
            // document.getElementById("content").style.display = "block";
            clearInterval(x);
          }
          //seconds
        }, 1000)
    }
  });

}(jQuery, Drupal, window));
