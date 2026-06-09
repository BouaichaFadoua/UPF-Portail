@extends('layouts.app')

@section('title', 'Assistant IA')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 flex flex-col" style="height: calc(100vh - 80px);">

    {{-- Header --}}
    <div class="mb-6 flex items-center gap-4">
        <div class="relative">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" />
                </svg>
            </div>
            <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-400 rounded-full border-2 border-white dark:border-slate-900 animate-pulse"></span>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Assistant UPF</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Posez vos questions sur vos notes, absences, emploi du temps ou tout autre sujet.
            </p>
        </div>
    </div>

    {{-- Chat Container --}}
    <div
        x-data="chatbot()"
        x-init="initChat()"
        class="flex flex-col flex-1 min-h-0 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-xl overflow-hidden"
    >
        {{-- Messages Area --}}
        <div
            id="chat-messages"
            x-ref="messages"
            class="flex-1 overflow-y-auto p-6 space-y-5 scroll-smooth"
        >
            {{-- Welcome bubble --}}
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    </svg>
                </div>
                <div class="max-w-[80%]">
                    <div class="bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-2xl rounded-tl-sm px-4 py-3 text-sm leading-relaxed shadow-sm">
                        👋 Bonjour <strong>{{ auth()->user()->name }}</strong> ! Je suis votre assistant universitaire IA.<br><br>
                        Je peux vous aider avec :
                        <ul class="mt-2 space-y-1 list-none">
                            <li>📊 <span class="font-medium">Vos notes</span> et moyennes</li>
                            <li>❌ <span class="font-medium">Vos absences</span> par module</li>
                            <li>📅 <span class="font-medium">Votre emploi du temps</span></li>
                            <li>🌍 <span class="font-medium">Questions générales</span> (sciences, culture, IA...)</li>
                        </ul>
                    </div>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 ml-1">Maintenant</p>
                </div>
            </div>

            {{-- Quick prompts --}}
            <div class="flex flex-wrap gap-2 pl-12">
                <template x-for="q in quickPrompts" :key="q">
                    <button
                        @click="sendQuick(q)"
                        class="text-xs px-3 py-1.5 rounded-full border border-indigo-200 dark:border-indigo-800 text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 transition-colors"
                        x-text="q"
                    ></button>
                </template>
            </div>

            {{-- Dynamic messages rendered here --}}
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex items-start gap-3'">

                    {{-- AI avatar (only for assistant) --}}
                    <template x-if="msg.role === 'assistant'">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                            </svg>
                        </div>
                    </template>

                    <div :class="msg.role === 'user' ? 'max-w-[80%]' : 'max-w-[80%]'">
                        <div
                            :class="msg.role === 'user'
                                ? 'bg-gradient-to-br from-indigo-600 to-violet-600 text-white rounded-2xl rounded-tr-sm px-4 py-3 text-sm leading-relaxed shadow'
                                : 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-2xl rounded-tl-sm px-4 py-3 text-sm leading-relaxed shadow-sm'"
                            x-html="formatMessage(msg.content)"
                        ></div>
                        <p
                            class="text-xs text-slate-400 dark:text-slate-500 mt-1"
                            :class="msg.role === 'user' ? 'text-right mr-1' : 'ml-1'"
                            x-text="msg.time"
                        ></p>
                    </div>
                </div>
            </template>

            {{-- Typing indicator --}}
            <div x-show="isTyping" class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    </svg>
                </div>
                <div class="bg-slate-100 dark:bg-slate-800 rounded-2xl rounded-tl-sm px-5 py-4 shadow-sm">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Error banner --}}
        <div
            x-show="error"
            x-transition
            class="mx-6 mb-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-sm text-red-600 dark:text-red-400 flex items-center gap-2"
        >
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
            </svg>
            <span x-text="error"></span>
        </div>

        {{-- Input Bar --}}
        <div class="border-t border-slate-200 dark:border-slate-700 p-4 bg-white dark:bg-slate-900">
            <form @submit.prevent="send()" class="flex items-end gap-3">
                <div class="flex-1 relative">
                    <textarea
                        id="chat-input"
                        x-ref="input"
                        x-model="inputMessage"
                        @keydown.enter.exact.prevent="send()"
                        @input="autoResize($el)"
                        placeholder="Posez votre question... (Entrée pour envoyer, Maj+Entrée pour nouvelle ligne)"
                        rows="1"
                        :disabled="isTyping"
                        class="w-full resize-none rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 px-4 py-3 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 disabled:opacity-50 transition-all"
                        style="max-height: 120px; overflow-y: auto;"
                    ></textarea>
                    {{-- char counter --}}
                    <span
                        class="absolute bottom-2 right-3 text-xs text-slate-400 dark:text-slate-500"
                        x-text="inputMessage.length + '/1000'"
                        x-show="inputMessage.length > 0"
                    ></span>
                </div>

                <button
                    type="submit"
                    :disabled="isTyping || inputMessage.trim().length < 2"
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-105 active:scale-95 transition-all disabled:opacity-40 disabled:hover:scale-100 disabled:cursor-not-allowed flex-shrink-0"
                    title="Envoyer (Entrée)"
                >
                    <svg x-show="!isTyping" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.269 20.876L5.999 12zm0 0h7.5"/>
                    </svg>
                    <svg x-show="isTyping" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </button>
            </form>

            <p class="text-xs text-center text-slate-400 dark:text-slate-600 mt-2">
                Les réponses sont générées par IA · Vos données restent privées et sécurisées
            </p>
        </div>
    </div>
</div>

@section('scripts')
<script>
function chatbot() {
    return {
        messages: [],
        inputMessage: '',
        isTyping: false,
        error: null,
        quickPrompts: [
            'Quelles sont mes notes ?',
            'Combien d\'absences ai-je ?',
            'Quel est mon emploi du temps cette semaine ?',
            'Quelle est ma moyenne générale ?',
        ],

        initChat() {
            // Optionally restore history from sessionStorage
        },

        async send() {
            const text = this.inputMessage.trim();
            if (text.length < 2 || this.isTyping) return;

            this.error    = null;
            this.inputMessage = '';
            this.$nextTick(() => { this.autoResize(this.$refs.input); });

            // Push user message
            this.messages.push({ role: 'user', content: text, time: this.now() });
            this.scrollDown();

            this.isTyping = true;

            try {
                const resp = await fetch('{{ route("etudiant.chatbot.message") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message: text }),
                });

                const data = await resp.json();

                if (!resp.ok) {
                    this.error = data.message || 'Une erreur est survenue.';
                } else {
                    this.messages.push({ role: 'assistant', content: data.reply, time: this.now() });
                }
            } catch (e) {
                this.error = 'Impossible de joindre le serveur. Vérifiez votre connexion.';
            } finally {
                this.isTyping = false;
                this.$nextTick(() => { this.scrollDown(); this.$refs.input.focus(); });
            }
        },

        sendQuick(q) {
            this.inputMessage = q;
            this.send();
        },

        scrollDown() {
            this.$nextTick(() => {
                const el = this.$refs.messages;
                if (el) el.scrollTop = el.scrollHeight;
            });
        },

        autoResize(el) {
            el.style.height = 'auto';
            el.style.height = Math.min(el.scrollHeight, 120) + 'px';
        },

        now() {
            return new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        },

        /**
         * Convert newlines to <br>, and bold **text** to <strong>.
         */
        formatMessage(text) {
            if (!text) return '';
            // Escape HTML first
            const escaped = text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
            // Bold: **text**
            const bolded = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            // Newlines → <br>
            return bolded.replace(/\n/g, '<br>');
        }
    };
}
</script>
@endsection
@endsection
