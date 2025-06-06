document.addEventListener("DOMContentLoaded", function () {
  const steps = document.querySelectorAll(".form-step");
  const nextBtn = document.getElementById("nextBtn");
  const prevBtn = document.getElementById("prevBtn");
  const submitBtn = document.getElementById("submitBtn");

  let currentStep = 0;
  showStep(currentStep);

  function showStep(index) {
    steps.forEach((step, i) => {
      step.classList.toggle("d-none", i !== index);
    });
    nextBtn.classList.toggle("d-none", index === steps.length - 1);
    submitBtn.classList.toggle("d-none", index !== steps.length - 1);
  }

  function updateValidationIcon(input, isValid, message = "") {
    const container = input.closest(".form-group") || input.parentElement;
    let feedback = container.querySelector(".validation-message");
    if (!feedback) {
      feedback = document.createElement("p");
      feedback.classList.add("validation-message", "text-danger", "mt-1");
      container.appendChild(feedback);
    }

    let icon = container.querySelector(".validity-icon");
    if (!icon) {
      icon = document.createElement("span");
      icon.classList.add("validity-icon", "ms-2");
      input.after(icon);
    }

    if (isValid) {
      input.classList.remove("is-invalid");
      input.classList.add("is-valid");
      feedback.textContent = "";
    } else {
      input.classList.remove("is-valid");
      input.classList.add("is-invalid");
      feedback.textContent = message;
    }
  }

  function capitalizeFirstLetter(str) {
    return str.charAt(0).match(/[a-zA-Z]/)
      ? str.charAt(0).toUpperCase() + str.slice(1)
      : str;
  }

  function validateInput(input) {
    let isValid = input.checkValidity();
    let message = "";

    if (!isValid) {
      message = "This field is required.";
    }

    if (input.id === "projectTitle") {
      const rawValue = input.value;
      const trimmedValue = rawValue.trim();
      const capitalizedValue = capitalizeFirstLetter(trimmedValue);

      if (trimmedValue && rawValue.charAt(0).match(/[a-zA-Z]/) && rawValue.charAt(0) !== capitalizedValue.charAt(0)) {
        input.value = capitalizedValue + rawValue.slice(1);
      }

      const title = input.value;
      const wordCount = title.trim().split(/\s+/).length;
      const onlyLettersRegex = /^[A-Za-z0-9\s.,'-]+$/;
      const hasLetter = /[A-Za-z]/;

      const letters = (title.match(/[A-Za-z]/g) || []).length;
      const digitsAndSymbols = (title.match(/[^A-Za-z\s]/g) || []).length;

      if (wordCount < 5 || wordCount > 12) {
        isValid = false;
        message = "Title must be between 5 and 12 words.";
      } else if (!hasLetter.test(title)) {
        isValid = false;
        message = "Title must include at least one letter.";
      } else if (!onlyLettersRegex.test(title)) {
        isValid = false;
        message = "Title contains invalid characters.";
      } else if (letters <= digitsAndSymbols) {
        isValid = false;
        message = "Title must contain more letters than numbers or symbols.";
      }
    }

    updateValidationIcon(input, isValid, message);
  }

  document.querySelectorAll("input[required], select[required], textarea[required]").forEach(input => {
    input.addEventListener("input", () => validateInput(input));
  });

  nextBtn.addEventListener("click", () => {
    const currentFormStep = steps[currentStep];
    const inputs = currentFormStep.querySelectorAll("input[required], select[required], textarea[required]");

    let allValid = true;
    inputs.forEach(input => {
      validateInput(input);
      if (!input.classList.contains("is-valid")) {
        allValid = false;
      }
    });

    if (allValid && currentStep < steps.length - 1) {
      currentStep++;
      showStep(currentStep);
    }
  });

  prevBtn.addEventListener("click", () => {
    if (currentStep == 0) {
      javascript:history.back()
    }
    if (currentStep > 0) {
      currentStep--;
      showStep(currentStep);
    }
  });

  const onlineRadio = document.getElementById('onlineOption');
  const offlineRadio = document.getElementById('offlineOption');
  const onlineGroup = document.getElementById('onlineGroup');
  const offlineGroup = document.getElementById('offlineGroup');
  const platform = document.getElementById('platform');
  const location = document.getElementById('location');
  const categorySelect = document.getElementById('projectCategory');

  const categoryOptions = {
    online: [
      "Front-end design",
      "Web development",
      "Copywriting",
      "Virtual assistance",
      "Digital marketing"
    ],
    offline: [
      "Construction",
      "Delivery services",
      "House cleaning",
      "Electrical repair",
      "Gardening"
    ]
  };

  function updateCategoryOptions() {
    const selectedType = onlineRadio.checked ? "online" : offlineRadio.checked ? "offline" : null;
    if (!selectedType) return;

    categorySelect.innerHTML = '<option value="" disabled selected>Select a category</option>';

    categoryOptions[selectedType].forEach(category => {
      const option = document.createElement("option");
      option.value = category;
      option.textContent = category;
      categorySelect.appendChild(option);
    });

    categorySelect.classList.remove("is-valid", "is-invalid");
  }

  function toggleFields() {
    onlineRadio.classList.remove("is-valid", "is-invalid");
    offlineRadio.classList.remove("is-valid", "is-invalid");

    platform.value = "";
    location.value = "";

    if (onlineRadio.checked) {
      onlineGroup.classList.remove('d-none');
      offlineGroup.classList.add('d-none');
      platform.removeAttribute('disabled');
      platform.setAttribute('required', 'required');
      location.setAttribute('disabled', 'disabled');
      location.removeAttribute('required');

      validateInput(onlineRadio);
    } else if (offlineRadio.checked) {
      offlineGroup.classList.remove('d-none');
      onlineGroup.classList.add('d-none');
      location.removeAttribute('disabled');
      location.setAttribute('required', 'required');
      platform.setAttribute('disabled', 'disabled');
      platform.removeAttribute('required');

      validateInput(offlineRadio);
    }

    updateCategoryOptions();
  }

  onlineRadio.addEventListener('change', toggleFields);
  offlineRadio.addEventListener('change', toggleFields);
  categorySelect.addEventListener("change", () => validateInput(categorySelect));
});
