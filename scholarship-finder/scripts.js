// Existing functions
function openUpdateForm(id, name, criteria_1, criteria_2, criteria_3, amount, deadline) {
    document.getElementById('update-form').style.display = 'block';
    document.getElementById('update-id').value = id;
    document.getElementById('update-name').value = name;
    document.getElementById('update-criteria_1').value = criteria_1;
    document.getElementById('update-criteria_2').value = criteria_2;
    document.getElementById('update-criteria_3').value = criteria_3;
    document.getElementById('update-amount').value = amount;
    document.getElementById('update-deadline').value = deadline;
}

function closeUpdateForm() {
    document.getElementById('update-form').style.display = 'none';
}

function openApplyForm(scholarshipId, scholarshipName) {
    document.getElementById('apply-form').style.display = 'block';
    document.getElementById('apply-scholarship-id').value = scholarshipId;
    document.getElementById('scholarship-name').textContent = scholarshipName;
}

function closeApplyForm() {
    document.getElementById('apply-form').style.display = 'none';
}

// New functions for editing students
function openEditStudentForm(id, name, email, phone, gpa) {
    document.getElementById('edit-student-form').style.display = 'block';
    document.getElementById('edit-student-id').value = id;
    document.getElementById('edit-student-name').value = name;
    document.getElementById('edit-student-email').value = email;
    document.getElementById('edit-student-phone').value = phone || '';
    document.getElementById('edit-student-gpa').value = gpa || '';
}

function closeEditStudentForm() {
    document.getElementById('edit-student-form').style.display = 'none';
}