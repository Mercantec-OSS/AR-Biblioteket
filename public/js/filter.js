document.addEventListener('DOMContentLoaded', function() {
    const educationFilter = document.getElementById('educationFilter');
    const modelItems = document.querySelectorAll('.model-item');

    educationFilter.addEventListener('change', function() {
        const selectedEducationId = this.value;

        modelItems.forEach(item => {
            if (!selectedEducationId) {
                // Show all items if no education is selected
                item.style.display = 'block';
                return;
            }

            // Get all education IDs for this model
            const educationTags = item.querySelectorAll('.education-tag');
            const hasEducation = Array.from(educationTags).some(tag => 
                tag.dataset.educationId === selectedEducationId
            );

            item.style.display = hasEducation ? 'block' : 'none';
        });
    });
}); 