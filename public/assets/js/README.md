# Edit Profile Component

A reusable component for editing user profiles with profile picture upload functionality.

## Features

- **Profile Picture Upload**: Drag & drop or click to upload profile pictures
- **Form Validation**: Built-in validation for all form fields
- **Data Persistence**: Automatically saves to localStorage
- **Responsive Design**: Works on all screen sizes
- **Modal Interface**: Clean, professional modal design
- **Real-time Updates**: Updates main profile display instantly

## Usage

### 1. Include the CSS and JS files

```html
<link rel="stylesheet" href="assets/css/edit-profile.css">
<script src="assets/js/edit-profile.js"></script>
```

### 2. Initialize the component

```javascript
// Initialize the component
const editProfileComponent = new EditProfileComponent();

// Show the edit profile modal
editProfileComponent.show();
```

### 3. HTML Structure

The component automatically creates the modal HTML. Just make sure you have:

```html
<!-- Main profile display -->
<div class="profile-avatar-container">
  <div class="profile-avatar" id="profileAvatar">
    <img src="" alt="Profile Picture" id="profileImage" style="display: none;">
    <i class="fas fa-user-graduate" id="defaultAvatar"></i>
  </div>
  <div class="avatar-upload-overlay">
    <label for="profilePictureInput" class="avatar-upload-btn" title="Change Profile Picture">
      <i class="fas fa-camera"></i>
    </label>
    <input type="file" id="profilePictureInput" accept="image/*" style="display: none;">
  </div>
</div>

<!-- Edit Profile Button -->
<button class="btn btn-outline btn-md edit-profile-btn">
  <i class="fas fa-edit"></i>
  Edit Profile
</button>
```

## Configuration

### Default Profile Data

```javascript
const editProfileComponent = new EditProfileComponent({
  name: 'John Doe',
  email: 'john.doe@example.com',
  major: 'Computer Science',
  year: '2',
  bio: 'Student bio...',
  phone: '+1 (555) 123-4567',
  location: 'City, State',
  linkedin: 'linkedin.com/in/username',
  github: 'github.com/username'
});
```

### Custom Fields

You can extend the component by adding custom fields:

```javascript
// Add custom fields to the form
editProfileComponent.addCustomField('interests', 'text', 'Interests', '');
editProfileComponent.addCustomField('skills', 'textarea', 'Skills', '');
```

## Events

The component emits events for integration:

```javascript
editProfileComponent.on('profileUpdated', (data) => {
  console.log('Profile updated:', data);
  // Update your UI here
});

editProfileComponent.on('pictureUploaded', (imageData) => {
  console.log('Picture uploaded:', imageData);
  // Handle picture upload
});
```

## Styling

### CSS Variables

The component uses CSS variables for consistent theming:

```css
:root {
  --primary: #0E2072;
  --accent: #0E2072;
  --foreground: #000000;
  --background: #ffffff;
  --border: #e5e7eb;
  --muted-foreground: #6b7280;
}
```

### Custom Styling

Override component styles:

```css
.edit-profile-modal {
  /* Your custom styles */
}

.profile-avatar {
  /* Custom avatar styles */
}
```

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Dependencies

- Font Awesome (for icons)
- CSS Grid support
- ES6+ JavaScript support

## License

This component is part of the GradBridge project.
