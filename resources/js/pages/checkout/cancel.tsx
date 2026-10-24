import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { show as checkoutShow } from '@/routes/checkout';
import { index as shopIndex } from '@/routes/shop';
import type { Order } from '@/types';

export default function CheckoutCancel({ order }: { order: Order | null }) {
    return (
        <>
            <Head title="Payment not completed" />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <Card className="max-w-lg">
                    <CardContent className="space-y-4 py-8">
                        <h1 className="text-xl font-semibold">Payment not completed</h1>

                        <p className="text-muted-foreground">
                            {order
                                ? `Order ${order.order_number} wasn't paid. You can try again whenever you're ready.`
                                : "Your payment wasn't completed."}
                        </p>

                        <div className="flex gap-3">
                            <Button asChild>
                                <Link href={checkoutShow()}>Try again</Link>
                            </Button>
                            <Button asChild variant="outline">
                                <Link href={shopIndex()}>Back to shop</Link>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

CheckoutCancel.layout = {
    breadcrumbs: [{ title: 'Checkout', href: '/checkout' }],
};
