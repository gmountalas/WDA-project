
document.addEventListener("DOMContentLoaded", () => {
  //////// CHANGE HEADER BUTTON COLORS DEPENDING ON PAGE
  const homeButton = document.querySelector(`#home-button`);
  const profileButton = document.querySelector(`#profile-button`);
  const $profileButton = document.querySelector(`.profile-button`);
  const $header = document.querySelector("header");

  if (document.body.getAttribute("data-pagetype") == "registration" || "login" || "list-page" || "profile-page" || "room-page") {
    homeButton.style.color = "black";
  }
  if (document.body.getAttribute("data-pagetype") == "index") {
    homeButton.style.color = "#e85929";
  }
  if (document.body.getAttribute("data-pagetype") == "registration" || "login" || "list-page" || "room-page") {
    profileButton.style.color = "black";
  }
  if (document.body.getAttribute("data-pagetype") == "profile-page") {
    profileButton.style.color = "#e85929";
  }
  ///// HIDE PROFILE BUTTON ON INDEX PAGE
  if (document.body.getAttribute(`data-pagetype`) == `index`) {
    $profileButton.style.visibility = "hidden";
    $header.style.backgroundColor = "#e6e9f1";
  }
  //////// CHANGE HEADER BUTTON COLORS ON MOUSE HOVER
  homeButton.addEventListener("mouseenter", (e) => {
    homeButton.style.color = "#a20d01";
  });
  homeButton.addEventListener("mouseleave", (e) => {
    if (document.body.getAttribute(`data-pagetype`) == `index`) {
      homeButton.style.color = "#e85929";
    } else {
      homeButton.style.color = "black";
    }
  });
  profileButton.addEventListener("mouseenter", (e) => {
    profileButton.style.color = "#a20d01";
  });
  profileButton.addEventListener("mouseleave", (e) => {
    if (document.body.getAttribute(`data-pagetype`) == `profile-page`) {
      profileButton.style.color = "#e85929";
    } else {
      profileButton.style.color = "black";
    }
  });
  ////


  const getRegisterValidations = ({
    Rfullname,
    Remail,
    RemailVer,
    Rpassword
  }) => {
    let RegfullnameIsValid = false;
    let RegisterEmailIsValid = false;
    let RegisterEmailVerIsVaild = false;
    let emailsMatchIsValid = false;

    if (
      Rfullname !== "" &&
      /^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/.test(Rfullname)
    ) {
      RegfullnameIsValid = true;
    }

    if (
      Remail !== "" &&
      /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(Remail)
    ) {
      RegisterEmailIsValid = true;
    }

    if (
      RemailVer !== "" &&
      /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(RemailVer)
    ) {
      RegisterEmailVerIsVaild = true;
    }
    if (RemailVer === Remail) {
      emailsMatchIsValid = true;
    } 

    return {
      RegisterEmailIsValid,
      RegfullnameIsValid,
      emailsMatchIsValid,
      RegisterEmailVerIsVaild,
    };
  };

  //////LOGIN VALIDATION
  const getValidations = ({ email, password }) => {
    let loginemailIsValid = false;
    let loginpasswordIsValid = false;

    if (
      email !== "" &&
      /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)
    ) {
      loginemailIsValid = true;
    } 
    
    if (password !== "" && password.length > 4) {
      loginpasswordIsValid = true;
    }

    return {
      loginemailIsValid,
      loginpasswordIsValid,
    };
  };

  //// INDEX FORM VALIDATION
  if (document.body.getAttribute("data-pagetype") == "index") {
    const $IndexForm = document.querySelector('#indexForm');

    const $check_In_Date = document.querySelector('#checkInDate');
    const $check_Out_Date = document.querySelector('#checkOutDate');
    const $check_In_Date_Error = document.querySelector('.check_in_date_error');
    const $check_Out_Date_Error = document.querySelector('.check_out_date_error');

    $IndexForm.addEventListener("submit", (e) => {
      e.preventDefault();

      let checkInDate_isValid = false;
      let checkOutDate_isValid = false;

      if ($check_In_Date.value === "") {
        $check_In_Date_Error.textContent = "Please enter a Check-in Date";
        document.querySelector('.check-in-date').classList.add("c-error");
      } else {
        $check_In_Date_Error.textContent = "";
        document.querySelector('.check-in-date').classList.remove("c-error");
        checkInDate_isValid = true;
      }
      if ($check_Out_Date.value === "") {
        $check_Out_Date_Error.textContent = "Please enter a Check-out Date";
        document.querySelector('.check-out-date').classList.add("c-error");
      } else if ($check_Out_Date.value <= $check_In_Date.value) {
        $check_Out_Date_Error.textContent = "Check-out Date can't be earlier than Check-in Date";
        document.querySelector('.check-out-date').classList.add("c-error");
      } else {
        $check_Out_Date_Error.textContent = "";
        document.querySelector('.check-out-date').classList.remove("c-error");
        checkOutDate_isValid = true;
      }
      if (checkInDate_isValid && checkOutDate_isValid) {
        $IndexForm.submit();
      }
    });
  }

  //// LOGIN FORM VALIDATION
  if (document.body.getAttribute("data-pagetype") == "login") {
    const $loginForm = document.querySelector("#loginForm");
    const $emailError = document.querySelector(".email-error");
    const $emailInput = document.querySelector("#LoginemailAddress");
    $emailError.style.display = 'none';

    $loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
      
      const { email, password } = e.target.elements;
      const loginvalues = {
        email: LoginemailAddress.value,
        password: LoginPassword.value,
      };

      const logvalidations = getValidations(loginvalues);
      if (logvalidations.loginemailIsValid == false) {
        $emailError.style.display = 'block';
        $emailInput.style.border = "2px solid red";
        document.querySelector('.login-email').classList.add("c-error");
      }
      if (
        logvalidations.loginemailIsValid &&
        logvalidations.loginpasswordIsValid
      ) {
        $loginForm.submit();
      }
    });  
  }
  ////REGISTER FORM VALIDATION
  if (document.body.getAttribute("data-pagetype") == "registration") {
    const $registerForm = document.querySelector("#registerForm");
    const $emailError = document.querySelector(".email-error");
    const $emailverError = document.querySelector(".email-ver-error");
    const $emailmatchError = document.querySelector(".email-match-error");
    const $emailInput = document.querySelector("#emailAddress");
    const $emailverInput = document.querySelector("#emailAddressVer");
    
    $emailError.style.display = 'none';
    $emailverError.style.display = 'none';
    $emailmatchError.style.display = 'none';

    $registerForm.addEventListener("submit", (e) => {
      e.preventDefault();
      
      const { Rfullname, Remail, RemailVer, Rpassword } = e.target.elements;
      const Rvalues = {
        Rfullname: fullName.value,
        Remail: emailAddress.value,
        RemailVer: emailAddressVer.value,
        Rpassword: formPassword.value,
      };

      const validations = getRegisterValidations(Rvalues);

      if (validations.RegisterEmailIsValid == false) {
        $emailError.style.display = 'block';
        $emailInput.style.border = "2px solid red";
        document.querySelector('.register-email').classList.add("c-error");
      } else {
        $emailError.style.display = 'none';
        $emailInput.style.border = 'none';
        $emailInput.style.borderBottom = "1px solid #000";
        document.querySelector('.register-email').classList.remove("c-error");
      }
      if (validations.RegisterEmailVerIsVaild == false) {
        $emailverError.style.display = 'block';
        $emailverInput.style.border = "2px solid red";
        document.querySelector('.register-email-ver').classList.add("c-error");
      } else {
        $emailverError.style.display = 'none';
        $emailverInput.style.border = "none !important";
        $emailverInput.style.borderBottom = "1px solid #000";
        document.querySelector('.register-email-ver').classList.remove("c-error");
      }
      if ((validations.RegisterEmailIsValid && validations.RegisterEmailVerIsVaild && validations.emailsMatchIsValid) == false) {
        $emailmatchError.style.display = 'block';
        $emailverInput.style.border = "2px solid red";
        document.querySelector('.register-email').classList.add("c-error");
        document.querySelector('.register-email-ver').classList.add("c-error");
      } else {
        $emailmatchError.style.display = 'none';
        $emailInput.style.border = "none";
        $emailInput.style.borderBottom = "1px solid #000";
        $emailverInput.style.border = "none";
        $emailverInput.style.borderBottom = "1px solid #000";
        document.querySelector('.register-email-ver').classList.remove("c-error");
      }

      if (
        validations.RegfullnameIsValid &&
        validations.RegisterEmailIsValid &&
        validations.RegisterEmailVerIsVaild &&
        validations.emailsMatchIsValid
      ) {
        $registerForm.submit();
      }

    });
    
  }
});
