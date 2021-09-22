let categoriesData = [];
let categoryPageSetting = {
  limit: 0,
  index: 0,
  total: 0,
  first: 0,
  last: 0,
};

const getCategories = () => {
  $.ajax({
    url:
      "https://seniorsdiscountclub.com.au/games/wordsearch/api/level/read.php",
    dataType: "json",
    type: "get",
    success: function (e) {
      categoriesData = e.records;
      categoryPageSetting.index = 0;
      setCategoryPageConfig();
      setTimeout(() => {
        hide(loader);
        displayCategories();
      }, 500);
    },
    error: function (err) {
      console.log(err);
    },
  });
};

const getCategoryByPermalink = async(permalink) => {
  return await $.ajax({
    url:
      "http://localhost/yourlifechoices-games/games/wordsearch/api/level/read-by-permalink.php",
    dataType: "json",
    type: "post",
    data: JSON.stringify({permalink: permalink}),
    success: function (e) {
      console.log(e);
    },
    error: function (err) {
      console.log(err);
    },
  });
};

const displayCategories = () => {
  categoryWrapper.empty();
  if (categoryPageSetting.index > categoryPageSetting.total) {
    categoryPageSetting.index = 0;
  }

  if (categoryPageSetting.index == 0) {
    prevButton.addClass("hide");
  } else {
    prevButton.removeClass("hide");
  }

  if (categoryPageSetting.index == categoryPageSetting.total - 1) {
    nextButton.addClass("hide");
  } else {
    nextButton.removeClass("hide");
  }

  categoriesData.forEach((cat, i) => {
    if (i >= categoryPageSetting.first && i <= categoryPageSetting.last) {
      let lastScore = "<div></div>";
      scoreData.forEach((x) => {
        if (x.levelId == categoriesData[i].id) {
          lastScore =
            '<div class="d-flex align-items-center" ><img src="./img/icon/star-icon.svg" alt="Score" class="category__thumb__star-icon mr-1" />' +
            '<span class="category__thumb__score text-uppercase text-white text-bolder">' +
            x.score +
            " pts</span></div>";
        }
      });
      let categoryThumb =
        '<div class="category__thumb ' +
        categoryClass[i % categoryClass.length] +
        ' flex-column justify-content-between clickable fadeIn" data-categoryId="' +
        categoriesData[i].id +
        '">' +
        lastScore +
        '<span class="h4 text-uppercase text-white text-bolder">' +
        categoriesData[i].name +
        "</span></div>";
      categoryWrapper.append(categoryThumb);
    }
  });
};

const setCategoryPageConfig = () => {
  if (windowWidth >= 768) {
    categoryPageSetting.limit = 8;
  } else {
    categoryPageSetting.limit = 4;
  }
  categoryPageSetting.total = Math.ceil(
    categoriesData.length / categoryPageSetting.limit
  );
  categoryPageSetting.first =
    categoryPageSetting.limit * categoryPageSetting.index;
  categoryPageSetting.last =
    categoryPageSetting.first + categoryPageSetting.limit - 1;
};

const nextCategoryPage = () => {
  if (categoryPageSetting.index < categoryPageSetting.total - 1) {
    categoryPageSetting.index++;
    setCategoryPageConfig();
    displayCategories();
  }
};

const prevCategoryPage = () => {
  if (categoryPageSetting.index !== 0) {
    categoryPageSetting.index--;
    setCategoryPageConfig();
    displayCategories();
  }
};
