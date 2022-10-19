
// This is your test publishable API key.
const stripe = Stripe("pk_test_51LuG0HAeLjIUesA8K30PSdXGqiNSTB0GhzjwtEqpC5rVoCrzb2zcaV7TQ6M7nGAnK0umVkMy7lQXxLkmtGsHXLyu00RrCTVKqK");

// The items the customer wants to buy
const items = [{ id: "xl-tshirt" }];

const clientSecret = "{{ clientSecret }}";

let elements;


 
    
  function initialize() {
    
      document
        .querySelector("#payment-form")
        .addEventListener("submit", handleSubmit);
    
     elements = stripe.elements({ clientSecret });
  
      const paymentElement = elements.create("payment");

      paymentElement.mount("#payment-element");
    }

    async function handleSubmit(e) {
      e.preventDefault();
      
      const {error} = await stripe.confirmPayment({
        elements,
        confirmParams: {
          return_url: '{{ url('purchase_payment_success', {'id': purchase.id}) }}',
        },
      });
    
 
    }

    async function checkStatus() {
      const clientSecret = new URLSearchParams(window.location.search).get(
        "payment_intent_client_secret"
      );

      if (!clientSecret) {
        return;
      }

      const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

      switch (paymentIntent.status) {
        case "succeeded":
          console.log("Payment succeeded!");
          break;
        case "processing":
          console.log("Your payment is processing.");
          break;
        case "requires_payment_method":
          console.log("Your payment was not successful, please try again.");
          break;
        default:
          console.log("Something went wrong.");
          break;
      }
    }
function showMessage(messageText) {
  const messageContainer = document.querySelector("#payment-message");

  messageContainer.classList.remove("hidden");
  messageContainer.textContent = messageText;

  setTimeout(function () {
    messageContainer.classList.add("hidden");
    messageText.textContent = "";
  }, 4000);
}

// Show a spinner on payment submission
function setLoading(isLoading) {
  if (isLoading) {
    // Disable the button and show a spinner
    document.querySelector("#submit").disabled = true;
    document.querySelector("#spinner").classList.remove("hidden");
    document.querySelector("#button-text").classList.add("hidden");
  } else {
    document.querySelector("#submit").disabled = false;
    document.querySelector("#spinner").classList.add("hidden");
    document.querySelector("#button-text").classList.remove("hidden");
  }
}

initialize();
    checkStatus();
    