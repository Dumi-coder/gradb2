(() => {
  const config = window.mentorshipChatConfig || {};
  const baseUrl = config.baseUrl || '';
  const currentUserId = String(config.currentUserId || '');

  const modal = document.getElementById('mentorshipChatModal');
  if (!modal || !baseUrl) {
    return;
  }

  const modalTitle = document.getElementById('mentorshipChatTitle');
  const closeButtons = modal.querySelectorAll('[data-chat-close]');
  const messagesEl = document.getElementById('mentorshipChatMessages');
  const emptyEl = document.getElementById('mentorshipChatEmpty');
  const form = document.getElementById('mentorshipChatForm');
  const input = document.getElementById('mentorshipChatInput');
  const threadStatus = document.getElementById('mentorshipChatStatus');
  const threadMeta = document.getElementById('mentorshipChatMeta');

  let activeThreadId = null;
  let activeThreadName = '';
  let pollTimer = null;

  function escapeHtml(value) {
    return String(value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function getMessageUrl(threadId) {
    return `${baseUrl}/messages/${threadId}`;
  }

  function getSendUrl(threadId) {
    return `${baseUrl}/sendMessage/${threadId}`;
  }

  function renderMessages(messages) {
    if (!messagesEl) return;

    if (!Array.isArray(messages) || messages.length === 0) {
      messagesEl.innerHTML = '';
      if (emptyEl) {
        emptyEl.style.display = 'flex';
      }
      return;
    }

    if (emptyEl) {
      emptyEl.style.display = 'none';
    }

    messagesEl.innerHTML = messages.map(message => {
      const isOwn = String(message.sender_user_id) === currentUserId;
      const time = message.created_at ? new Date(message.created_at).toLocaleString() : '';
      return `
        <div class="chat-message ${isOwn ? 'chat-message-self' : 'chat-message-other'}">
          <div class="chat-message-bubble">
            <div class="chat-message-header">
              <span class="chat-message-name">${escapeHtml(message.sender_name || 'User')}</span>
              <span class="chat-message-time">${escapeHtml(time)}</span>
            </div>
            <div class="chat-message-body">${escapeHtml(message.message_body || '')}</div>
          </div>
        </div>
      `;
    }).join('');

    messagesEl.scrollTop = messagesEl.scrollHeight;
  }

  async function loadMessages() {
    if (!activeThreadId) return;

    try {
      const response = await fetch(getMessageUrl(activeThreadId), {
        headers: { 'Accept': 'application/json' },
      });
      const data = await response.json();

      if (!data.success) {
        if (threadStatus) {
          threadStatus.textContent = data.message || 'Unable to load chat.';
        }
        return;
      }

      const thread = data.thread || {};
      if (threadStatus) {
        threadStatus.textContent = thread.status ? `Status: ${String(thread.status).replace(/_/g, ' ')}` : '';
      }
      if (threadMeta) {
        threadMeta.textContent = activeThreadName ? `Chat with ${activeThreadName}` : '';
      }
      renderMessages(data.messages || []);
    } catch (error) {
      if (threadStatus) {
        threadStatus.textContent = 'Failed to load chat.';
      }
    }
  }

  function openModal(threadId, threadName) {
    activeThreadId = threadId;
    activeThreadName = threadName || 'Mentorship Chat';

    if (modalTitle) {
      modalTitle.textContent = activeThreadName;
    }
    if (threadMeta) {
      threadMeta.textContent = `Chat with ${activeThreadName}`;
    }
    if (threadStatus) {
      threadStatus.textContent = 'Loading chat...';
    }

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('chat-open');

    loadMessages();
    clearInterval(pollTimer);
    pollTimer = window.setInterval(loadMessages, 4000);
    if (input) {
      input.focus();
    }
  }

  function closeModal() {
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('chat-open');
    clearInterval(pollTimer);
    pollTimer = null;
    activeThreadId = null;
    activeThreadName = '';
    if (form) {
      form.reset();
    }
    if (messagesEl) {
      messagesEl.innerHTML = '';
    }
    if (emptyEl) {
      emptyEl.style.display = 'flex';
    }
  }

  document.addEventListener('click', event => {
    const trigger = event.target.closest('.mentorship-chat-open');
    if (trigger) {
      event.preventDefault();
      const threadId = trigger.getAttribute('data-thread-id');
      const threadName = trigger.getAttribute('data-thread-name');
      if (threadId) {
        openModal(threadId, threadName);
      }
      return;
    }

    if (event.target === modal) {
      closeModal();
    }
  });

  closeButtons.forEach(button => {
    button.addEventListener('click', closeModal);
  });

  if (form) {
    form.addEventListener('submit', async event => {
      event.preventDefault();
      if (!activeThreadId || !input) return;

      const message = input.value.trim();
      if (!message) {
        return;
      }

      const formData = new FormData();
      formData.append('message', message);

      try {
        const response = await fetch(getSendUrl(activeThreadId), {
          method: 'POST',
          body: formData,
          headers: { 'Accept': 'application/json' },
        });
        const data = await response.json();

        if (data.success) {
          input.value = '';
          await loadMessages();
        } else if (threadStatus) {
          threadStatus.textContent = data.message || 'Failed to send message.';
        }
      } catch (error) {
        if (threadStatus) {
          threadStatus.textContent = 'Failed to send message.';
        }
      }
    });
  }

  window.addEventListener('beforeunload', () => {
    clearInterval(pollTimer);
  });
})();
