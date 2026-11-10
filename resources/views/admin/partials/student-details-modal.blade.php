<dialog id="student-details-modal" class="w-[min(42rem,calc(100%-2rem))] rounded-2xl border-0 p-0 shadow-2xl backdrop:bg-slate-900/50">
    <div class="max-h-[85vh] overflow-y-auto bg-white p-6 sm:p-8">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-brand-600">Student account</p>
                <h2 id="student-details-name" class="mt-1 text-2xl font-bold text-slate-900"></h2>
            </div>
            <button type="button" id="student-details-close" class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Close</button>
        </div>

        <dl class="mt-6 grid gap-4 rounded-2xl border border-slate-100 bg-slate-50 p-4 sm:grid-cols-2">
            <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</dt><dd id="student-details-email" class="mt-1 break-all text-sm font-medium text-slate-900"></dd></div>
            <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone</dt><dd id="student-details-phone" class="mt-1 text-sm font-medium text-slate-900"></dd></div>
            <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Index number</dt><dd id="student-details-index" class="mt-1 text-sm font-medium text-slate-900"></dd></div>
            <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Account status</dt><dd id="student-details-status" class="mt-1 text-sm font-medium text-slate-900"></dd></div>
        </dl>

        <section class="mt-6">
            <h3 class="text-base font-bold text-slate-900">Status history</h3>
            <ol id="student-status-history" class="mt-3 space-y-3"></ol>
        </section>

        <div class="mt-6 flex justify-end border-t border-slate-100 pt-5">
            <button type="button" id="student-status-action" class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2"></button>
        </div>
    </div>
</dialog>

<script>
    (() => {
        const modal = document.getElementById('student-details-modal');
        const closeButton = document.getElementById('student-details-close');
        const statusAction = document.getElementById('student-status-action');
        let activeStatusForm = null;
        let nextActiveStatus = null;

        const setText = (id, value) => {
            document.getElementById(id).textContent = value || 'Not provided';
        };

        document.querySelectorAll('.student-details-trigger').forEach((button) => {
            button.addEventListener('click', () => {
                const details = JSON.parse(button.dataset.studentDetails);
                activeStatusForm = document.getElementById(`student-status-form-${details.id}`);
                nextActiveStatus = !details.is_active;

                setText('student-details-name', details.full_name);
                setText('student-details-email', details.email);
                setText('student-details-phone', details.phone_number);
                setText('student-details-index', details.student_id_number);
                setText('student-details-status', details.is_active ? 'Active' : 'Inactive');
                statusAction.textContent = details.is_active ? 'Deactivate account' : 'Activate account';
                statusAction.className = details.is_active
                    ? 'rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2'
                    : 'rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2';

                const history = document.getElementById('student-status-history');
                history.replaceChildren();
                if (details.status_history.length === 0) {
                    const empty = document.createElement('li');
                    empty.className = 'rounded-xl border border-slate-100 p-4 text-sm text-slate-600';
                    empty.textContent = 'No account status changes have been recorded.';
                    history.append(empty);
                } else {
                    details.status_history.forEach((event) => {
                        const item = document.createElement('li');
                        item.className = 'rounded-xl border border-slate-100 p-4 text-sm text-slate-700';
                        item.textContent = `${event.old_status || 'Created'} → ${event.new_status} by ${event.actor} on ${event.changed_at || 'date unavailable'}.`;
                        history.append(item);
                    });
                }

                modal.showModal();
            });
        });

        statusAction.addEventListener('click', () => {
            if (!activeStatusForm) return;
            activeStatusForm.querySelector('[name="is_active"]').value = nextActiveStatus ? '1' : '0';
            activeStatusForm.requestSubmit();
        });

        closeButton.addEventListener('click', () => modal.close());
        modal.addEventListener('click', (event) => {
            if (event.target === modal) modal.close();
        });
    })();
</script>
