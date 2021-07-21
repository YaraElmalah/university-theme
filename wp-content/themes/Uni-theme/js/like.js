(function ($) {
  class Like {
    constructor() {
      this.events();
    }
    events() {
      $(".like-box").on("click", this.ourClickedDispatcher.bind(this));
    }
    //methods
    ourClickedDispatcher(e) {
      //decide which function to trigger
      /**
       * closest => get the ancestor elements
       */
      var currentLikeBox = $(e.target).closest(".like-box");
      if (currentLikeBox.attr("data-exists") === "yes") {
        this.deleteLike(currentLikeBox);
      } else {
        this.createLike(currentLikeBox);
      }
    }
    createLike(currentLikeBox) {
      let LikedProfessor = currentLikeBox.data("professor");
      $.ajax({
        beforeSend: (xhr) => {
          xhr.setRequestHeader("X-WP-Nonce", likes_info.nonce);
        },
        url: likes_info.root_url + "/wp-json/university/v1/manageLike",
        data: { professorID: LikedProfessor },
        type: "POST",
        success: (response) => {
          currentLikeBox.attr("data-exists", "yes");
          let likeCount = parseInt(
            currentLikeBox.find(".like-count").html(),
            10
          ); //this 10 for decimal
          likeCount++;
          currentLikeBox.find(".like-count").html(likeCount); //increase number of likes
          currentLikeBox.attr("data-like", response);
          console.table(response);
          console.log(currentLikeBox);
        },
        error: (response) => {
          console.table(response);
        },
      });
    }
    deleteLike(currentLikeBox) {
      $.ajax({
        url: uni_info.root_url + "/wp-json/university/v1/manageLike",
        beforeSend: (xhr) => {
          xhr.setRequestHeader("X-WP-Nonce", likes_info.nonce);
        },
        type: "DELETE",
        data: { like: currentLikeBox.attr("data-like") },
        success: (response) => {
          currentLikeBox.attr("data-exists", "no");
          let likeCount = parseInt(
            currentLikeBox.find(".like-count").html(),
            10
          ); //this 10 for decimal
          likeCount--;
          currentLikeBox.find(".like-count").html(likeCount); //increase number of likes
          currentLikeBox.attr("data-like", response);
          console.log(currentLikeBox);
          console.table(response);
        },
        error: (response) => {
          console.table(response);
        },
      });
    }
  }
  let like = new Like();
})(jQuery);
