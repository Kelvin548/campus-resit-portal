<dialog id="account-details-modal" class="w-[min(42rem,calc(100%-2rem))] rounded-2xl border-0 p-0 shadow-2xl backdrop:bg-slate-900/50">
    <div class="max-h-[85vh] overflow-y-auto bg-white p-6 sm:p-8">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-brand-600">Account details</p>
                <h2 id="account-details-name" class="mt-1 text-2xl font-bold text-slate-900"></h2>
            </div>
            <button type="button" id="account-details-close" class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Close</button>
        </div>

        <dl class="mt-6 grid gap-4 rounded-2xl border border-slate-100 bg-slate-50 p-4 sm:grid-cols-2">
            <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</dt><dd id="account-details-email" class="mt-1 break-all text-sm font-medium text-slate-900"></dd></div>
            <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone</dt><dd id="account-details-phone" class="mt-1 text-sm font-medium text-slate-900"></dd></div>
            <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Role</dt><dd id="account-details-role" class="mt-1 text-sm font-medium text-slate-900"></dd></div>
            <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</dt><dd id="account-details-status" class="mt-1 text-sm font-medium text-slate-900"></dd></div>
        </dl>

        <section class="mt-6">
            <h3 class="text-base font-bold text-slate-900">Invitation history</h3>
            <div id="account-invitation-history" class="mt-3 rounded-xl border border-slate-100 p-4 text-sm text-slate-600"></div>
        </section>

        <section class="mt-6">
            <h3 class="text-base font-bold text-slate-900">Account status history</h3>
            <ol id="account-status-history" class="mt-3 space-y-3"></ol>
        </section>

        <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5">
            <p id="staff-status-action-note" class="text-sm text-slate-500"></p>
            <button type="button" id="staff-status-action" class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2"></button>
        </div>
    </div>
</dialog>

<script>
    (() => {
        const modal = document.getElementById('account-details-modal');
        const closeButton = document.getElementById('account-details-close');
        const statusAction = document.getElementById('staff-status-action');
        const statusActionNote = document.getElementById('staff-status-action-note');
        let activeStatusForm = null;
        let nextStatus = null;
        const setText = (id, value) => {
            document.getElementById(id).textContent = value || 'Not provided';
        };

        document.querySelectorAll('.account-details-trigger').forEach((button) => {
            button.addEventListener('click', () => {
                const details = JSON.parse(button.dataset.accountDetails);
                activeStatusForm = document.getElementById(`staff-status-form-${details.id}`);
                nextStatus = !details.is_active;
                setText('account-details-name', details.full_name);
                setText('account-details-email', details.email);
                setText('account-details-phone', details.phone_number);
                setText('account-details-role', details.role === 'admin' ? 'Administrator' : 'Chief Examiner');
                setText('account-details-status', details.is_active ? 'Active' : 'Inactive');
                statusAction.textContent = details.is_active ? 'Deactivate account' : 'Activate account';
                statusAction.className = details.is_active
                    ? 'rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2'
                    : 'rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2';
                statusAction.disabled = Boolean(details.is_self);
                statusActionNote.textContent = details.is_self
                    ? 'You cannot deactivate your own Administrator account.'
                    : 'Status changes are recorded in the account history.';
                if (details.is_self) {
                    statusAction.textContent = 'Your account';
                    statusAction.className = 'rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-500';
                }

                const invitationHistory = document.getElementById('account-invitation-history');
                invitationHistory.replaceChildren();
                if (details.invitation) {
                    const issued = document.createElement('p');
                    issued.textContent = `Invitation issued by ${details.invitation.issuer || 'Unknown'} on ${details.invitation.issued_at || 'date unavailable'}.`;
                    const accepted = document.createElement('p');
                    accepted.className = 'mt-2';
                    accepted.textContent = `Accepted by ${details.invitation.accepted_by || 'Unknown'} on ${details.invitation.accepted_at || 'date unavailable'}.`;
                    invitationHistory.append(issued, accepted);
                } else {
                    invitationHistory.textContent = 'No accepted staff invitation is linked to this account.';
                }

                const statusHistory = document.getElementById('account-status-history');
                statusHistory.replaceChildren();
                if (details.status_history.length === 0) {
                    const empty = document.createElement('li');
                    empty.className = 'rounded-xl border border-slate-100 p-4 text-sm text-slate-600';
                    empty.textContent = 'No account status changes have been recorded.';
                    statusHistory.append(empty);
                } else {
                    details.status_history.forEach((event) => {
                        const item = document.createElement('li');
                        item.className = 'rounded-xl border border-slate-100 p-4 text-sm text-slate-700';
                        item.textContent = `${event.old_status || 'Created'} → ${event.new_status} by ${event.actor} on ${event.changed_at || 'date unavailable'}.`;
                        statusHistory.append(item);
                    });
                }

                modal.showModal();
            });
        });

        statusAction.addEventListener('click', () => {
            if (!activeStatusForm || statusAction.disabled) return;
            activeStatusForm.querySelector('[name="is_active"]').value = nextStatus ? '1' : '0';
            activeStatusForm.requestSubmit();
        });

        closeButton.addEventListener('click', () => modal.close());
        modal.addEventListener('click', (event) => {
            if (event.target === modal) modal.close();
        });
    })();
</script>
