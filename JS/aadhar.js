document.addEventListener("DOMContentLoaded", function () {
    const aadhaarInput = document.getElementById("aadhaar");
    const verifyButton = document.getElementById("verifyAadhaar");
    const resultMessage = document.getElementById("resultMessage");
    const aadhaarForm = document.getElementById("aadhaarForm");

    // Restrict input to numbers only & max 12 digits
    aadhaarInput.addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, "").slice(0, 12);
    });

    // Handle Aadhaar verification on form submission
    aadhaarForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const aadhaarNumber = aadhaarInput.value.trim();
        resultMessage.innerHTML = "";

        if (aadhaarNumber.length !== 12) {
            resultMessage.innerHTML = `<span class="text-red-500">⚠ Aadhaar number must be 12 digits.</span>`;
            return;
        }

        verifyButton.disabled = true;
        verifyButton.innerText = "Verifying...";

        fetch("../backend/aadhaar_verify.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `aadhaar=${aadhaarNumber}`
        })
        .then(response => response.json())
        .then(data => {
            verifyButton.disabled = false;
            verifyButton.innerText = "Verify Aadhaar";

            if (data.success) {
                resultMessage.innerHTML = `<span class="text-green-500">✅ Aadhaar verified successfully!</span>`;
                setTimeout(() => window.location.href = "dashboard.php", 1000);
            } else {
                resultMessage.innerHTML = `<span class="text-red-500">❌ ${data.message}</span>`;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            resultMessage.innerHTML = `<span class="text-red-500">⚠ Server error. Try again later.</span>`;
            verifyButton.disabled = false;
            verifyButton.innerText = "Verify Aadhaar";
        });
    });
});
