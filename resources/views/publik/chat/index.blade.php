@extends('layouts.publik')

@section('title', 'Hubungi Master Admin | Gayo Heritage')

@section('content')
<!-- Watermark Background -->
<div class="fixed inset-0 kerawang-pattern pointer-events-none opacity-20 z-0"></div>

<!-- Background Accents -->
<div class="fixed -top-24 -left-24 w-96 h-96 bg-[#0D9373] opacity-5 rounded-full blur-3xl z-0"></div>
<div class="fixed -bottom-24 -right-24 w-96 h-96 bg-primary-container opacity-5 rounded-full blur-3xl z-0"></div>

<main class="min-h-screen pt-24 px-margin-mobile md:px-margin-desktop relative z-10 flex flex-col justify-center pb-12">
    <div class="max-w-3xl w-full mx-auto h-[75vh] min-h-[500px] glass rounded-2xl shadow-2xl border border-[#0D9373]/10 overflow-hidden flex flex-col">
        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-[#F0F7F4] to-[#F0F7F4] p-6 flex items-center text-white border-b border-[#0D9373]/10 relative overflow-hidden">
            <div class="absolute inset-0 kerawang-pattern opacity-10"></div>
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('login') }}" class="mr-4 hover:bg-white/20 p-2 rounded-full transition-colors z-10">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
            </a>
            <div class="flex items-center gap-4 z-10">
                <div class="relative bg-white/20 p-3 rounded-full backdrop-blur-sm shadow-inner">
                    <span class="material-symbols-outlined text-2xl">support_agent</span>
                    <span class="absolute bottom-0.5 right-0.5 w-3.5 h-3.5 bg-[#0D9373] rounded-full border-2 border-[#0A7D62]"></span>
                </div>
                <div>
                    <h3 class="font-headline-md text-xl font-bold leading-tight">Master Admin Seni</h3>
                    <p class="text-sm text-white/80 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#0D9373]"></span> Online, siap membantu
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Chat Body -->
        <div id="chatBody" class="flex-1 p-6 overflow-y-auto bg-surface-container-lowest/50 flex flex-col gap-4">
            <!-- Loading Indicator -->
            <div class="flex flex-col items-center justify-center h-full text-on-surface-variant/50" id="chatEmptyState">
                <span class="material-symbols-outlined text-5xl mb-4 animate-pulse">forum</span>
                <p class="text-sm">Menghubungkan ke layanan chat...</p>
            </div>
        </div>

        <!-- Chat Input -->
        <form id="chatForm" class="p-5 bg-surface-container-lowest border-t border-[#0D9373]/10 flex gap-3 items-center backdrop-blur-md" onsubmit="sendChatMessage(event)">
            <input type="text" id="chatMessage" class="flex-1 bg-surface-container-high border-none rounded-full px-6 py-3.5 focus:ring-1 focus:ring-primary-container text-on-surface shadow-inner outline-none transition-all placeholder:text-on-surface-variant/50" placeholder="Ketik pesan Anda..." required autocomplete="off">
            <button type="submit" class="bg-primary-container text-[#3a3000] w-12 h-12 rounded-full flex items-center justify-center hover:bg-primary transition-colors disabled:opacity-50 shadow-lg active:scale-95" id="btnSendChat">
                <span class="material-symbols-outlined text-xl">send</span>
            </button>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
    let chatInterval = null;
    let lastMessageCount = 0;

    // Load messages on startup
    document.addEventListener("DOMContentLoaded", () => {
        loadMessages();
        chatInterval = setInterval(loadMessages, 3000); // Poll every 3 seconds
    });

    async function loadMessages() {
        try {
            const response = await fetch('{{ route("publik.chat.messages") }}');
            if (!response.ok) return;
            const messages = await response.json();
            
            if (messages.length === 0) {
                const emptyState = document.getElementById('chatEmptyState');
                if (emptyState) {
                    emptyState.innerHTML = `
                        <span class="material-symbols-outlined text-5xl mb-4">forum</span>
                        <p class="text-sm">Silakan kirim pesan untuk memulai obrolan dengan admin</p>
                    `;
                }
            } else if (messages.length > 0) {
                document.getElementById('chatEmptyState')?.remove();
            }

            // Only update DOM if new messages arrived
            if (messages.length !== lastMessageCount) {
                const chatBody = document.getElementById('chatBody');
                chatBody.innerHTML = '';
                
                messages.forEach(msg => {
                    const isVisitor = !msg.is_admin;
                    const alignment = isVisitor ? 'self-end bg-primary-container text-[#3a3000]' : 'self-start bg-surface-container-high text-on-surface border border-[#0D9373]/8';
                    const borderRadius = isVisitor ? 'rounded-2xl rounded-tr-sm' : 'rounded-2xl rounded-tl-sm';
                    const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    
                    const messageHtml = `
                        <div class="max-w-[80%] md:max-w-[70%] ${alignment} ${borderRadius} px-5 py-3 shadow-md relative group">
                            <p class="text-[15px] leading-relaxed break-words">${msg.message.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>
                            <span class="text-[10px] opacity-70 mt-1.5 block ${isVisitor ? 'text-right' : 'text-left'}">${time}</span>
                        </div>
                    `;
                    chatBody.insertAdjacentHTML('beforeend', messageHtml);
                });
                
                chatBody.scrollTop = chatBody.scrollHeight;
                lastMessageCount = messages.length;
            }
        } catch (error) {
            console.error('Failed to load messages', error);
        }
    }

    async function sendChatMessage(e) {
        e.preventDefault();
        const input = document.getElementById('chatMessage');
        const message = input.value.trim();
        if (!message) return;
        
        const btnSend = document.getElementById('btnSendChat');
        input.disabled = true;
        btnSend.disabled = true;

        try {
            const response = await fetch('{{ route("publik.chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: message })
            });

            if (response.ok) {
                input.value = '';
                loadMessages();
            }
        } catch (error) {
            console.error('Error sending message', error);
        } finally {
            input.disabled = false;
            btnSend.disabled = false;
            input.focus();
        }
    }
</script>
@endpush
