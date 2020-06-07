class Gallery {
  
  constructor (num1, num2) {
    this.summ = 0
  }
  
  setSize (e) {
    $(e.target).find('[type=number]').each( function() { $(this).css('width','85px') } )
  }

  calculatePrice () {
    let price = parseInt($('#price-per-foot').val())
    let shipping = parseInt($('#shipping').val())
    let height = parseInt($('#size2').val())
    let width = parseInt($('#size1').val())
    let coupon = $('#coupon').val()
    let tax = $('#tax').val()
    let summ = 0  
    // Calculate modifiers
    if ($('#installation').is(':checked')) {
      let addPrice = parseInt($('#installation-price').val())
      price += addPrice
    }
    // tax and shipping
    summ = (width * height * price) / 144
    summ = Math.round(summ * 100) / 100
    //summ += summ * tax  
    //summ += shipping  
    if (coupon !== '') {
        this.checkCoupon(coupon, summ)
    } else {
        this.displaySumm(summ)
        $('.code-message').html('')
    }
    this.summ = summ
  }

  setSumm() {
      
  }
  
  displaySumm (summ) {
    let value
    if (summ !== 0 && !isNaN(summ) && summ >= 5) value = 'Pay $' + summ + ' CAD'
    else if (summ < 5 && summ !== 0) value = 'Minimum amount is $5 CAD'
    else if (summ == 0) value = 'Select size to buy'
    $('#summ').val(summ)
    $("input[type='submit']").val(value)
  }

  checkCoupon (code, summ) {
    let data = {
      'code': code,
      'summ': summ
    }
    $.when(
      $.ajax({
        url: '/index.php?controller=code&action=check',
        type: 'POST',
        data: data,
        dataType: 'json'
      })
    ).then((result)=>{
      if(result.error === undefined){
        console.log(result);
        this.summ = result.summ
        this.displaySumm(result.summ)
        // Add a field if one time code
        // display message if summ is to low
        if (result.msg !== '') {
            $('.code-message').html(result.msg)
        } else {
            $('.code-message').html('Coupon is activated!')
        }
      } else {
        $('.code-message').html('Code not valid!')
        this.displaySumm(this.summ)
      }
    })  
  }

  processSize (el) {
    if ($(el).val() < 0) $(el).val(0)
    let summ = 0
    this.calculatePrice()
  }

  processForm (controller, action) {
    //Validate form
    let isValid = this.validateForm()
    if (isValid) {
      let form = $('.form-to-process')
      let qStr = '/index.php?controller=' + controller + '&action=' + action
      form.attr('action', qStr)
      // remove captcha event
      form.unbind('submit')
      form.get(0).submit()
    } else {
      // Scroll to the top of form
      $([document.documentElement, document.body]).animate({
        scrollTop: $('.dmform-wrapper').offset().top - 140
      }, 600)
      return false
    }
  }

  validateForm () {
    let amount
    let isValid = true
    let email = $("input[name='email']")
    //Check the email
    if (email.length !== 0 && !this.isEmail(email.val())) {
      isValid = false
      email.addClass('inputError')
    } else {
      email.removeClass('inputError')
    }
    // Validate an height and width fields
    $("input[type='number']").each((k,e) => {
      let  el = $(e)
      amount  = parseInt(el.val())
      if (amount == 0 || isNaN(amount)) {
        isValid = false
        el.addClass('inputError')
      } else {
        el.removeClass('inputError')
      }
    })
    // Iterate throug required fields
    $('.mandatory').each( function() {
      let el = $(this)
      if (el.val().length == '') {
        isValid = false
        el.addClass('inputError')
      } else {
        el.removeClass('inputError')
      }
    })
    if (this.summ < 5) {
      isValid = false
      $("input[type='submit']").val('Minimum amount is $5 CAD')
    }
    return isValid
  }

  isEmail (email) {
    // One or more times any latin letter and digits 
    // then same with optional dot zero ore more times, 
    // then @,  one or more times any latin letter and digits
    // then one or more times any latin letter and digits with optional dot,
    // then 2 or 3 times any latin letter and digits prefixed with required dot
    let expr = /^[a-zA-Z0-9]+([\.-]?[a-zA-Z0-9]+)*@[a-zA-Z0-9]+([\.-]?[a-zA-Z0-9]+)*(\.[a-zA-Z0-9]{2,3})+$/
    if (expr.test(email)) return true
    return false
  }
}

const baseUrl = 'https://wallpaper.3ddesigncanada.com'
const CustomGallery = new Gallery()

$(document).ready(function () {
  CustomGallery.calculatePrice()
  $('.has-link').on('click', function(e) {
  window.location = $(e.target).attr('href')
  })
})