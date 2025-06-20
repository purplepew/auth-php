const infoForm = document.getElementById("infoForm")
const infoResponse = document.getElementById('infoResponse')
const welcomeDiv = document.querySelector('.welcome')

infoForm.addEventListener('submit', handleSubmitInfo)

async function handleSubmitInfo(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const firstName = formData.get('First name')?.trim();
    const lastName = formData.get('Last name')?.trim();
    const gender = formData.get('Gender')?.trim();
    const birthday = formData.get('Birthday')?.trim();
    const address = formData.get('Address')?.trim();

    infoResponse.style.color = 'firebrick';
    infoResponse.textContent = '';

    try {
        // First name
        if (!firstName) throw { message: 'First name is required.' };
        if (firstName.length > 15) throw { message: 'First name must be 15 characters max.' };
        if (!/^[a-zA-Z]+$/.test(firstName)) throw { message: 'First name must contain only letters.' };

        // Last name
        if (!lastName) throw { message: 'Last name is required.' };
        if (!/^[a-zA-Z]+$/.test(lastName)) throw { message: 'Last name must contain only letters.' };

        // Gender
        if (!gender) throw { message: 'Gender is required.' };
        if (!/^(Male|Female|Other)$/i.test(gender)) {
            throw { message: 'Gender must be Male, Female, or Other.' };
        }

        // Birthday
        if (!birthday) throw { message: 'Birthday is required.' };
        const today = new Date().toISOString().split('T')[0];
        if (birthday >= today) throw { message: 'Birthday must be a valid past date.' };

        // Address
        if (!address) throw { message: 'Address is required.' };
        if (address.length < 5) throw { message: 'Address must be at least 5 characters.' };

        const response = await fetch('./utils/infoController.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (!response.ok) throw new Error(result.message);

        infoResponse.style.color = 'green';
        infoResponse.textContent = result.message;
        e.target.reset();

        setTimeout(() => {
            infoForm.remove();
            const msg = document.createElement('p');
            msg.textContent = 'You’ve successfully completed your profile!';
            msg.style.color = 'green';
            welcomeDiv.appendChild(msg);
        }, 300)


    } catch (error) {
        infoResponse.textContent = error.message || 'Saving info failed.';
        infoResponse.classList.remove('show');
        void infoResponse.offsetWidth;
        infoResponse.classList.add('show');
    }
}