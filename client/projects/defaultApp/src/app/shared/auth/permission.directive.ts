import {Directive, Input, OnChanges, SimpleChanges, TemplateRef, ViewContainerRef} from '@angular/core';
import {AuthService} from "./auth.service";

@Directive({
  selector: '[appPermissions]'
})
export class PermissionDirective implements OnChanges  {
  private visible = false;

  constructor(private viewContainerRef: ViewContainerRef, private templateRef: TemplateRef<any>, private authService: AuthService) {
  }

  @Input() appPermissions: string[] = [];
  @Input() appPermissionsParam: any = null;

  ngOnChanges(changes: SimpleChanges) {
    this.updateView();
  }

  private updateView() {
    if (this.authService.hasPermissions(this.appPermissions, this.appPermissionsParam)) {
      if (!this.visible) {
        this.viewContainerRef.createEmbeddedView(this.templateRef);
        this.visible = true;
      }
    } else {
      this.visible = false;
      this.viewContainerRef.clear();
    }
  }
}
