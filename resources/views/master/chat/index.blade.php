@extends('layouts.master')

@section('title', 'Pesan Masuk')

@section('content')
<div class="glass-panel p-6 rounded-2xl h-[calc(100vh-200px)] flex gap-6">
    
    <!-- Sidebar: Daftar Sesi Chat -->
    <div class="w-1/3 border-r border-outline-variant/30 pr-4 flex flex-col h-full">
        <h2 class="font-headline-md text-[20px] font-bold text-on-surface mb-4">Percakapan</h2>
        
        <div class="flex-1 overflow-y-auto space-y-2 pr-2" id="sessionList">
            @forelse($sessions as $session)
                <div class="p-3 rounded-xl cursor-pointer hover:bg-surface-variant/30 transition-colors border border-transparent hover:border-outline-variant/50 session-item" onclick="openChat('{{ $session->session_id }}')" id="session_{{ $session->session_id }}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-primary">person</span>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <h4 class="text-sm font-semibold text-on-surface truncate">Pengunjung {{ strtoupper(substr($session->session_id, 0, 5)) }}</h4>
                            <p class="text-xs text-on-surface-variant truncate">{{ \Carbon\Carbon::parse($session->last_activity)->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-on-surface-variant/60 text-sm">
                    Belum ada percakapan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Area Obrolan Aktif -->
    <div class="flex-1 flex flex-col h-full relative">
        <div id="chatEmptyState" class="absolute inset-0 flex flex-col items-center justify-center text-on-surface-variant/50 z-10 bg-surface/50 backdrop-blur-sm rounded-xl">
            <span class="material-symbols-outlined text-[64px] mb-4">forum</span>
            <p>Pilih percakapan untuk mulai membalas</p>
        </div>

        <!-- Header -->
        <div class="border-b border-outline-variant/30 pb-4 mb-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">person</span>
            </div>
            <div>
                <h3 class="font-bold text-on-surface">Pengunjung</h3>
                <p class="text-xs text-on-surface-variant" id="activeSessionId">Sesi: -</p>
            </div>
        </div>

        <!-- Pesan -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="adminChatBody">
            <!-- Messages will load here -->
        </div>

        <!-- Input -->
        <form class="mt-4 pt-4 border-t border-outline-variant/30 flex gap-2" id="adminChatForm" onsubmit="sendReply(event)">
            <input type="text" id="adminChatMessage" class="flex-1 bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Ketik balasan..." disabled autocomplete="off" required>
            <button type="submit" class="bg-primary text-white w-12 rounded-xl flex items-center justify-center hover:bg-primary/90 transition-colors disabled:opacity-50" id="btnAdminSendChat" disabled>
                <span class="material-symbols-outlined">send</span>
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let activeSession = null;
    let chatInterval = null;

    function openChat(sessionId) {
        activeSession = sessionId;
        document.getElementById('chatEmptyState').classList.add('hidden');
        document.getElementById('activeSessionId').textContent = 'Sesi: ' + sessionId.substring(0, 8) + '...';
        
        // Update header name
        const visitorId = sessionId.substring(0, 5).toUpperCase();
        document.querySelector('.border-b.border-outline-variant\\/30 h3').textContent = 'Pengunjung ' + visitorId;
        
        // Aktifkan input
        document.getElementById('adminChatMessage').disabled = false;
        document.getElementById('btnAdminSendChat').disabled = false;
        
        // Highlight session
        document.querySelectorAll('.session-item').forEach(el => el.classList.remove('bg-surface-variant/50', 'border-primary/50'));
        document.getElementById('session_' + sessionId).classList.add('bg-surface-variant/50', 'border-primary/50');
        
        loadAdminMessages();
        
        if(chatInterval) clearInterval(chatInterval);
        chatInterval = setInterval(loadAdminMessages, 3000);
    }

    async function loadAdminMessages() {
        if(!activeSession) return;
        
        try {
            const response = await fetch(`{{ url('master/chat') }}/${activeSession}`);
            if(!response.ok) return;
            const messages = await response.json();
            
            const chatBody = document.getElementById('adminChatBody');
            chatBody.innerHTML = '';
            
            messages.forEach(msg => {
                const isAdmin = msg.is_admin;
                const alignment = isAdmin ? 'self-end bg-primary/20 text-on-surface' : 'self-start bg-surface-container-high text-on-surface';
                const borderRadius = isAdmin ? 'rounded-2xl rounded-tr-sm' : 'rounded-2xl rounded-tl-sm';
                const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                
                const messageHtml = `
                    <div class="flex flex-col ${isAdmin ? 'items-end' : 'items-start'}">
                        <span class="text-[10px] text-on-surface-variant/70 mb-1 ml-1">${isAdmin ? 'Anda' : 'Pengunjung'}</span>
                        <div class="max-w-[70%] ${alignment} ${borderRadius} px-4 py-2.5 text-sm shadow-sm">
                            <p>${msg.message.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>
                            <span class="text-[9px] opacity-70 mt-1 block text-right">${time}</span>
                        </div>
                    </div>
                `;
                chatBody.insertAdjacentHTML('beforeend', messageHtml);
            });
            
            chatBody.scrollTop = chatBody.scrollHeight;
        } catch (error) {
            console.error('Failed to load messages', error);
        }
    }

    async function sendReply(e) {
        e.preventDefault();
        if(!activeSession) return;
        
        const input = document.getElementById('adminChatMessage');
        const message = input.value.trim();
        if(!message) return;
        
        const btnSend = document.getElementById('btnAdminSendChat');
        input.disabled = true;
        btnSend.disabled = true;

        try {
            const response = await fetch(`{{ url('master/chat') }}/${activeSession}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: message })
            });

            if(response.ok) {
                input.value = '';
                loadAdminMessages();
            }
        } catch (error) {
            console.error('Error sending reply', error);
        } finally {
            input.disabled = false;
            btnSend.disabled = false;
            input.focus();
        }
    }
</script>
@endpush
