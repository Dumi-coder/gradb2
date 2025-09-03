// Dashboard JavaScript - Alumni Management System

// Mock Data Arrays
const mockData = {
    mentorshipRequests: [
        {
            id: 1,
            name: "Sarah Johnson",
            university: "Stanford University",
            reason: "Looking for guidance on career transition to product management",
            status: "New"
        },
        {
            id: 2,
            name: "Mike Chen",
            university: "MIT",
            reason: "Need advice on startup funding and investor relations",
            status: "Pending"
        },
        {
            id: 3,
            name: "Emily Rodriguez",
            university: "UC Berkeley",
            reason: "Seeking mentorship for software engineering career path",
            status: "In Progress"
        }
    ],
    
    aidRequests: [
        {
            id: 1,
            name: "Emily Rodriguez",
            type: "Financial Aid",
            description: "Emergency medical expenses for family member",
            urgent: true
        },
        {
            id: 2,
            name: "David Kim",
            type: "Scholarship",
            description: "Support for graduate studies in computer science",
            urgent: false
        },
        {
            id: 3,
            name: "Lisa Wang",
            type: "Emergency Fund",
            description: "Housing assistance during difficult times",
            urgent: true
        }
    ],
    
    forumPosts: [
        {
            id: 1,
            title: "Career advice for recent graduates",
            preview: "I'm graduating next month and would love to hear from alumni about their career paths...",
            timestamp: "2 hours ago",
            replies: 12,
            views: 89
        },
        {
            id: 2,
            title: "Networking event in San Francisco",
            preview: "Anyone interested in organizing a networking event for Bay Area alumni?",
            timestamp: "1 day ago",
            replies: 8,
            views: 45
        },
        {
            id: 3,
            title: "Industry insights: Tech trends 2024",
            preview: "Let's discuss the latest trends in technology and how they affect our careers...",
            timestamp: "3 days ago",
            replies: 15,
            views: 120
        }
    ],
    
    events: [
        {
            id: 1,
            title: "Alumni Homecoming 2024",
            description: "Annual gathering with networking and keynote speakers",
            date: "Dec 15, 2024",
            time: "6:00 PM",
            location: "Main Campus"
        },
        {
            id: 2,
            title: "Career Fair",
            description: "Connect with top employers and explore opportunities",
            date: "Jan 20, 2025",
            time: "10:00 AM",
            location: "Virtual"
        },
        {
            id: 3,
            title: "Mentorship Workshop",
            description: "Learn how to be an effective mentor and guide students",
            date: "Feb 5, 2025",
            time: "2:00 PM",
            location: "Engineering Building"
        }
    ],
    
    resources: [
        {
            id: 1,
            name: "Resume Template 2024",
            timestamp: "Updated 2 days ago",
            type: "pdf"
        },
        {
            id: 2,
            name: "Interview Preparation Guide",
            timestamp: "Updated 1 week ago",
            type: "doc"
        },
        {
            id: 3,
            name: "Networking Best Practices",
            timestamp: "Updated 2 weeks ago",
            type: "pdf"
        },
        {
            id: 4,
            name: "Career Development Roadmap",
            timestamp: "Updated 3 weeks ago",
            type: "pdf"
        }
    ],
    
    badges: [
        {
            id: 1,
            name: "Mentor",
            description: "Helped 5+ students",
            icon: "👥",
            color: "blue"
        },
        {
            id: 2,
            name: "Donor",
            description: "Contributed $1000+",
            icon: "💰",
            color: "green"
        },
        {
            id: 3,
            name: "Volunteer",
            description: "50+ hours served",
            icon: "🤝",
            color: "purple"
        },
        {
            id: 4,
            name: "Speaker",
            description: "Presented at events",
            icon: "🎤",
            color: "yellow"
        }
    ]
};

// DOM Elements
const elements = {
    sidebar: document.getElementById('sidebar'),
    sidebarToggle: document.getElementById('sidebarToggle'),
    mentorshipCards: document.getElementById('mentorship-cards'),
    aidCards: document.getElementById('aid-cards'),
    forumPosts: document.getElementById('forum-posts'),
    eventsCards: document.getElementById('events-cards'),
    resourcesList: document.getElementById('resources-list'),
    badgesGrid: document.getElementById('badges-grid')
};

// Utility Functions
const utils = {
    // Get status color class
    getStatusColor: (status) => {
        switch (status) {
            case 'New': return 'status-new';
            case 'Pending': return 'status-pending';
            case 'In Progress': return 'status-progress';
            default: return 'status-pending';
        }
    },
    
    // Format timestamp
    formatTimestamp: (timestamp) => {
        return timestamp;
    },
    
    // Create SVG icon
    createIcon: (iconName) => {
        const iconMap = {
            'message': '<svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>',
            'eye': '<svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>',
            'clock': '<svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/><path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>',
            'location': '<svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>',
            'download': '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>'
        };
        return iconMap[iconName] || '';
    }
};

// Render Functions
const render = {
    // Render mentorship request cards
    mentorshipCards: () => {
        const container = elements.mentorshipCards;
        container.innerHTML = '';
        
        mockData.mentorshipRequests.forEach(request => {
            const card = document.createElement('div');
            card.className = 'mentorship-card fade-in';
            card.innerHTML = `
                <div class="card-header">
                    <div>
                        <h4 class="card-title">${request.name}</h4>
                        <p class="card-subtitle">${request.university}</p>
                    </div>
                    <span class="status-badge ${utils.getStatusColor(request.status)}">${request.status}</span>
                </div>
                <p class="card-description">${request.reason}</p>
                <div class="card-actions">
                    <button class="btn btn-primary" onclick="handleMentorshipAction(${request.id}, 'accept')">
                        Accept
                    </button>
                    <button class="btn btn-secondary" onclick="handleMentorshipAction(${request.id}, 'view')">
                        View Profile
                    </button>
                </div>
            `;
            container.appendChild(card);
        });
    },
    
    // Render aid request cards
    aidCards: () => {
        const container = elements.aidCards;
        container.innerHTML = '';
        
        mockData.aidRequests.forEach(request => {
            const card = document.createElement('div');
            card.className = `aid-card fade-in ${request.urgent ? 'urgent' : ''}`;
            card.innerHTML = `
                <div class="card-header">
                    <div>
                        <h4 class="card-title">${request.name}</h4>
                        <p class="card-subtitle">${request.type}</p>
                    </div>
                    ${request.urgent ? '<span class="urgent-badge">Urgent</span>' : ''}
                </div>
                <p class="card-description">${request.description}</p>
                <div class="card-actions">
                    <button class="btn btn-primary" onclick="handleAidAction(${request.id}, 'provide')">
                        Provide Aid
                    </button>
                    <button class="btn btn-secondary" onclick="handleAidAction(${request.id}, 'view')">
                        View Details
                    </button>
                </div>
            `;
            container.appendChild(card);
        });
    },
    
    // Render forum posts
    forumPosts: () => {
        const container = elements.forumPosts;
        container.innerHTML = '';
        
        mockData.forumPosts.forEach(post => {
            const postElement = document.createElement('div');
            postElement.className = 'forum-post fade-in';
            postElement.innerHTML = `
                <div class="post-header">
                    <h4 class="post-title">${post.title}</h4>
                    <span class="post-timestamp">${post.timestamp}</span>
                </div>
                <p class="post-preview">${post.preview}</p>
                <div class="post-meta">
                    <span>${utils.createIcon('message')} ${post.replies} replies</span>
                    <span>${utils.createIcon('eye')} ${post.views} views</span>
                </div>
            `;
            container.appendChild(postElement);
        });
    },
    
    // Render event cards
    eventCards: () => {
        const container = elements.eventsCards;
        container.innerHTML = '';
        
        mockData.events.forEach(event => {
            const card = document.createElement('div');
            card.className = 'event-card fade-in';
            card.innerHTML = `
                <div class="card-header">
                    <h4 class="card-title">${event.title}</h4>
                    <span class="post-timestamp">${event.date}</span>
                </div>
                <p class="card-description">${event.description}</p>
                <div class="event-meta">
                    <span>${utils.createIcon('clock')} ${event.time}</span>
                    <span>${utils.createIcon('location')} ${event.location}</span>
                </div>
            `;
            container.appendChild(card);
        });
    },
    
    // Render resources list
    resourcesList: () => {
        const container = elements.resourcesList;
        container.innerHTML = '';
        
        mockData.resources.forEach(resource => {
            const item = document.createElement('div');
            item.className = 'resource-item fade-in';
            item.innerHTML = `
                <div class="resource-info">
                    <div class="resource-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                        </svg>
                    </div>
                    <div class="resource-details">
                        <h4>${resource.name}</h4>
                        <p class="resource-timestamp">${resource.timestamp}</p>
                    </div>
                </div>
                <button class="download-btn" onclick="handleResourceAction(${resource.id}, 'download')" title="Download">
                    ${utils.createIcon('download')}
                </button>
            `;
            container.appendChild(item);
        });
    },
    
    // Render badges grid
    badgesGrid: () => {
        const container = elements.badgesGrid;
        container.innerHTML = '';
        
        mockData.badges.forEach(badge => {
            const badgeElement = document.createElement('div');
            badgeElement.className = 'badge-card fade-in';
            badgeElement.innerHTML = `
                <div class="badge-icon ${badge.color}">
                    <span style="font-size: 1.5rem;">${badge.icon}</span>
                </div>
                <h4 class="badge-name">${badge.name}</h4>
                <p class="badge-description">${badge.description}</p>
            `;
            container.appendChild(badgeElement);
        });
    },
    
    // Render all sections
    all: () => {
        render.mentorshipCards();
        render.aidCards();
        render.forumPosts();
        render.eventCards();
        render.resourcesList();
        render.badgesGrid();
    }
};

// Event Handlers
const handlers = {
    // Handle mentorship actions
    mentorshipAction: (id, action) => {
        const request = mockData.mentorshipRequests.find(r => r.id === id);
        if (!request) return;
        
        if (action === 'accept') {
            request.status = 'Accepted';
            showNotification(`Mentorship request from ${request.name} accepted!`);
        } else if (action === 'view') {
            showNotification(`Viewing profile of ${request.name}`);
        }
        
        render.mentorshipCards();
    },
    
    // Handle aid actions
    aidAction: (id, action) => {
        const request = mockData.aidRequests.find(r => r.id === id);
        if (!request) return;
        
        if (action === 'provide') {
            showNotification(`Aid provided to ${request.name}`);
        } else if (action === 'view') {
            showNotification(`Viewing details for ${request.name}'s aid request`);
        }
    },
    
    // Handle resource actions
    resourceAction: (id, action) => {
        const resource = mockData.resources.find(r => r.id === id);
        if (!resource) return;
        
        if (action === 'download') {
            showNotification(`Downloading ${resource.name}`);
        }
    },
    
    // Handle sidebar toggle
    sidebarToggle: () => {
        const sidebar = elements.sidebar;
        sidebar.classList.toggle('collapsed');
        
        // Update main content margin
        const mainContent = document.querySelector('.main-content');
        if (sidebar.classList.contains('collapsed')) {
            mainContent.style.marginLeft = '70px';
        } else {
            mainContent.style.marginLeft = '280px';
        }
    },
    
    // Handle navigation
    navigation: (section) => {
        // Remove active class from all nav items
        document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // Add active class to clicked item
        const activeItem = document.querySelector(`[data-section="${section}"]`).parentElement;
        activeItem.classList.add('active');
        
        showNotification(`Navigating to ${section} section`);
    }
};

// Global handler functions (accessible from HTML)
window.handleMentorshipAction = handlers.mentorshipAction;
window.handleAidAction = handlers.aidAction;
window.handleResourceAction = handlers.resourceAction;

// Notification system
const showNotification = (message) => {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification fade-in';
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #2563eb;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        max-width: 300px;
        font-size: 0.875rem;
        font-weight: 500;
    `;
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
};

// Initialize dashboard
const initDashboard = () => {
    // Render all sections
    render.all();
    
    // Add event listeners
    if (elements.sidebarToggle) {
        elements.sidebarToggle.addEventListener('click', handlers.sidebarToggle);
    }
    
    // Add navigation event listeners
    document.querySelectorAll('.nav-link[data-section]').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const section = e.currentTarget.getAttribute('data-section');
            handlers.navigation(section);
        });
    });
    
    // Add button event listeners
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            // Add click effect
            btn.style.transform = 'scale(0.95)';
            setTimeout(() => {
                btn.style.transform = '';
            }, 150);
        });
    });
    
    // Add hover effects to cards
    document.querySelectorAll('.mentorship-card, .aid-card, .forum-post, .event-card, .badge-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
    
    console.log('Alumni Dashboard initialized successfully!');
};

// Initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDashboard);
} else {
    initDashboard();
}

// Export for potential use in other scripts
window.DashboardApp = {
    render,
    handlers,
    mockData,
    showNotification
};
