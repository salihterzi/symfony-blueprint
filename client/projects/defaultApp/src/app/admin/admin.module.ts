import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AdminRoutingModule } from './admin-routing.module';
import {ReactiveFormsModule} from '@angular/forms';
import { DashboardComponent } from './dashboard/dashboard.component';
import {SharedModule} from '../shared/shared.module';
import { TestComponent } from './test/test.component';


@NgModule({
  declarations: [DashboardComponent, TestComponent],
  imports: [
    CommonModule,
    AdminRoutingModule,
    ReactiveFormsModule,
    SharedModule
  ]
})
export class AdminModule { }
