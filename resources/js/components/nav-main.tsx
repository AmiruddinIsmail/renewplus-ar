"use client";
import {
    SidebarGroup,
    SidebarGroupContent,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from "@/components/ui/sidebar";
import { Link } from "@inertiajs/react";
import DynamicIcon from "./dynamic-icon";
import { Menu } from "@/types";

type Props = {
    menu: Menu[];
};

export function NavMain({ menu }: Props) {
    return (
        <SidebarGroup>
            <SidebarGroupContent>
                <SidebarMenu>
                    {menu.map((item) => (
                        <SidebarMenuItem key={item.title}>
                            <SidebarMenuButton
                                asChild
                                isActive={route()
                                    .current()
                                    ?.startsWith(item.name)}
                            >
                                <Link
                                    href={item.link}
                                    prefetch={
                                        !route().current()?.includes(item.name)
                                    }
                                    cacheFor="5m"
                                >
                                    <DynamicIcon name={item.icon} />
                                    {/* <item.icon /> */}
                                    <span>{item.title}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    ))}
                </SidebarMenu>
            </SidebarGroupContent>
        </SidebarGroup>
    );
}
