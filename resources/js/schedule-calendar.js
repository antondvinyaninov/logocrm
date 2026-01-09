export default (initialWorkingDays, initialTemplates) => ({
    currentMonth: new Date(),
    workingDays: initialWorkingDays || {},
    savedTemplates: initialTemplates || [
        { name: 'Будни', start: '09:00', end: '18:00', breakStart: '13:00', breakEnd: '14:00', hasBreak: true, workingDaysOfWeek: [1, 2, 3, 4, 5] },
        { name: 'Выходные', start: '10:00', end: '15:00', breakStart: '', breakEnd: '', hasBreak: false, workingDaysOfWeek: [6, 7] }
    ],
    showTemplateModal: false,
    newTemplateName: '',
    templateToSave: null,
    openMenuDay: null,
    editingTemplateIndex: null,
    
    toggleDayOfWeek(dayIndex) {
        if (!this.templateToSave.workingDaysOfWeek) {
            this.templateToSave.workingDaysOfWeek = [];
        }
        const index = this.templateToSave.workingDaysOfWeek.indexOf(dayIndex);
        if (index > -1) {
            this.templateToSave.workingDaysOfWeek.splice(index, 1);
        } else {
            this.templateToSave.workingDaysOfWeek.push(dayIndex);
        }
    },
    
    isDayOfWeekWorking(dayIndex) {
        return this.templateToSave?.workingDaysOfWeek?.includes(dayIndex) || false;
    },
    
    get monthName() {
        return this.currentMonth.toLocaleDateString('ru-RU', { month: 'long', year: 'numeric' });
    },
    
    prevMonth() {
        this.currentMonth = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth() - 1);
    },
    
    nextMonth() {
        this.currentMonth = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth() + 1);
    },
    
    getDaysInMonth() {
        const year = this.currentMonth.getFullYear();
        const month = this.currentMonth.getMonth();
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startDayOfWeek = (firstDay.getDay() + 6) % 7;
        
        const days = [];
        for (let i = 0; i < startDayOfWeek; i++) {
            days.push(null);
        }
        for (let day = 1; day <= daysInMonth; day++) {
            days.push(day);
        }
        return days;
    },
    
    toggleDay(day) {
        if (!day) return;
        const dateKey = `${this.currentMonth.getFullYear()}-${String(this.currentMonth.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        if (this.workingDays[dateKey]) {
            delete this.workingDays[dateKey];
        } else {
            this.workingDays[dateKey] = { 
                start: '09:00', 
                end: '18:00',
                breakStart: '',
                breakEnd: '',
                hasBreak: false
            };
        }
    },
    
    toggleBreak(day) {
        if (!day) return;
        const dateKey = `${this.currentMonth.getFullYear()}-${String(this.currentMonth.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const workTime = this.workingDays[dateKey];
        if (workTime) {
            workTime.hasBreak = !workTime.hasBreak;
            if (!workTime.hasBreak) {
                workTime.breakStart = '';
                workTime.breakEnd = '';
            }
        }
    },
    
    applyTemplateToMonth(template) {
        const year = this.currentMonth.getFullYear();
        const month = this.currentMonth.getMonth();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        const weekdayTemplate = this.savedTemplates[0] || { start: '09:00', end: '18:00', breakStart: '13:00', breakEnd: '14:00', hasBreak: true, workingDaysOfWeek: [1, 2, 3, 4, 5] };
        const weekendTemplate = this.savedTemplates[1] || { start: '10:00', end: '16:00', breakStart: '', breakEnd: '', hasBreak: false, workingDaysOfWeek: [6, 7] };
        
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dayOfWeek = date.getDay();
            const dayIndex = dayOfWeek === 0 ? 7 : dayOfWeek;
            const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            
            if (template === 'all') {
                if (weekdayTemplate.workingDaysOfWeek?.includes(dayIndex)) {
                    this.workingDays[dateKey] = { 
                        start: weekdayTemplate.start,
                        end: weekdayTemplate.end,
                        breakStart: weekdayTemplate.breakStart,
                        breakEnd: weekdayTemplate.breakEnd,
                        hasBreak: weekdayTemplate.hasBreak
                    };
                } else if (weekendTemplate.workingDaysOfWeek?.includes(dayIndex)) {
                    this.workingDays[dateKey] = { 
                        start: weekendTemplate.start,
                        end: weekendTemplate.end,
                        breakStart: weekendTemplate.breakStart,
                        breakEnd: weekendTemplate.breakEnd,
                        hasBreak: weekendTemplate.hasBreak
                    };
                }
            } else if (template === 'weekdays') {
                if (weekdayTemplate.workingDaysOfWeek?.includes(dayIndex)) {
                    this.workingDays[dateKey] = { 
                        start: weekdayTemplate.start,
                        end: weekdayTemplate.end,
                        breakStart: weekdayTemplate.breakStart,
                        breakEnd: weekdayTemplate.breakEnd,
                        hasBreak: weekdayTemplate.hasBreak
                    };
                }
            } else if (template === 'weekends') {
                if (weekendTemplate.workingDaysOfWeek?.includes(dayIndex)) {
                    this.workingDays[dateKey] = { 
                        start: weekendTemplate.start,
                        end: weekendTemplate.end,
                        breakStart: weekendTemplate.breakStart,
                        breakEnd: weekendTemplate.breakEnd,
                        hasBreak: weekendTemplate.hasBreak
                    };
                }
            } else if (template === 'clear') {
                delete this.workingDays[dateKey];
            }
        }
    },
    
    saveAsTemplate(day) {
        const workTime = this.getWorkTime(day);
        if (!workTime) return;
        
        this.templateToSave = { ...workTime };
        this.editingTemplateIndex = null;
        this.newTemplateName = '';
        this.showTemplateModal = true;
    },
    
    editTemplate(index) {
        this.editingTemplateIndex = index;
        this.templateToSave = { ...this.savedTemplates[index] };
        if (!this.templateToSave.workingDaysOfWeek) {
            this.templateToSave.workingDaysOfWeek = [];
        }
        this.newTemplateName = this.savedTemplates[index].name;
        this.showTemplateModal = true;
    },
    
    confirmSaveTemplate() {
        if (!this.newTemplateName.trim()) {
            alert('Введите название шаблона');
            return;
        }
        
        if (this.editingTemplateIndex !== null) {
            this.savedTemplates[this.editingTemplateIndex] = {
                name: this.newTemplateName,
                ...this.templateToSave
            };
        } else {
            this.savedTemplates.push({
                name: this.newTemplateName,
                ...this.templateToSave
            });
        }
        
        this.showTemplateModal = false;
        this.newTemplateName = '';
        this.templateToSave = null;
        this.editingTemplateIndex = null;
    },
    
    applyTemplate(template, day) {
        if (!day) return;
        const dateKey = `${this.currentMonth.getFullYear()}-${String(this.currentMonth.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        this.workingDays[dateKey] = {
            start: template.start,
            end: template.end,
            breakStart: template.breakStart,
            breakEnd: template.breakEnd,
            hasBreak: template.hasBreak
        };
    },
    
    isWorking(day) {
        if (!day) return false;
        const dateKey = `${this.currentMonth.getFullYear()}-${String(this.currentMonth.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        return !!this.workingDays[dateKey];
    },
    
    getWorkTime(day) {
        if (!day) return null;
        const dateKey = `${this.currentMonth.getFullYear()}-${String(this.currentMonth.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        return this.workingDays[dateKey] || null;
    },
    
    async saveCalendar() {
        try {
            const response = await fetch('/lk/specialists/calendar/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    workingDays: this.workingDays,
                    templates: this.savedTemplates
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                notification.textContent = 'Календарь успешно сохранён';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            } else {
                alert('Ошибка при сохранении календаря');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Произошла ошибка при сохранении');
        }
    }
});
